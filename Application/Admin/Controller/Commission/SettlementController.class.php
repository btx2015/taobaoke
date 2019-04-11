<?php

namespace Admin\Controller\Commission;

use Admin\Controller\CommonController;

class SettlementController extends CommonController
{

    const T_SETTLE = 'tr_commission';

    const T_DETAIL = 'tr_commission_detail';

    const T_CHANNEL = 'tr_channel';

    const T_ORDER = 'tr_commission_order';

    const T_MEMBER = 'tr_member';

    const T_FLOW = 'tr_member_fund_flow';

    public function index(){
        if(IS_POST){
            $model = M(self::T_SETTLE);
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'settle_sn'   => [[],false,true,['like','settlement_sn']],
                'channel_id'  => [['num']],
                'state'       => [['in'=>[1,2,3]],false,true,['eq','state']],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ],false);
            $list = $model->where($where)->page($pageNo,$pageSize)->select();

            $channels = [];
            if($list){
                $channelId = array_unique(array_column($list,'channel_id'));
                $channels = M(self::T_CHANNEL)->field('id,name')->where(['id'=>['in',$channelId]])->select();
                $channels = array_column($channels,'name','id');
            }

            returnResult([
                'list' => handleRecords([
                    'channel_id' => ['array_walk',$channels,'channel_id_str'],
                    'state'      => ['translate','settle_state','state_str'],
                    'created_at' => ['time','Y-m-d H:i:s','created_at_str'],
                ],$list),
                'total' => $model->where($where)->count()
            ]);
        }else{
            $channels = M(self::T_CHANNEL)->field('id,name')->where('state = 1')->select();
            $this->assign('channels',$channels);
            $this->display();
        }
    }

    public function add(){
        $id = I('post.id');
        if(!$id)
            showError(10006);
        $channel = M(self::T_CHANNEL)->where(['id'=>$id])->find();
        if(!$channel)
            showError(20000);

        $insertId = M(self::T_SETTLE)->add([
            'settlement_sn'=> date('YmdHis'),
            'channel_id'   => $id,
            'fee_rate'     => $channel['fee_rate'],
            'channel_rate' => $channel['channel_rate'],
            'referee_rate' => $channel['referee_rate'],
            'grand_rate'   => $channel['grand_rate'],
            'created_at'   => time()
        ]);
        if(!$insertId)
            showError(20001);//创建失败
        returnResult();
    }

    public function settle(){
        $id = I('post.id');
        if(!$id)
            showError(10006);
        $settleModel = M(self::T_SETTLE);
        $settle = $settleModel->where(['id' => $id,'state' => 1])->find();
        if(!$settle)
            showError(20004,'结算单不存在');
        $orderModel = M(self::T_ORDER);
        $orders = $orderModel->where([
            'channel_id' => $id,
            'state'      => 2
        ])->limit(100)->select();
        if(!$orders){
            M()->startTrans();
            //计算渠道收入
            $fee = round($settle['channel_amount'] * $settle['channel_rate'],2);
            $realAmount = $settle['channel_amount'] - $fee;
            $res = $settleModel->where(['id'=>$id])->save([
                'fee_amount'  => $fee,
                'real_amount' => $realAmount
            ]);
            if(!$res){
                M()->rollback();
                showError(20001,'结算单信息更新失败');
            }
            //修改结算单状态
            $res = $settleModel->where(['id' => $id])->save([
                'state' => 2,'settle_time' => time()
            ]);
            if(!$res){
                M()->rollback();
                showError(20001,'结算单信息更新失败');
            }
            M()->commit();
            showError(20004,'暂无未结算的订单');
        }
        $details = [];
        $totalAmount = $channelTotalAmount = $grandTotalAmount = 0;
        $refereeTotalAmount = $userTotalAmount = $memberNum = 0;
        $time = time();
        foreach($orders as $order){
            $totalAmount += $order['commission_fee'];
            $memberNum ++;
            $detail = [
                'settle_id' => $id,
                'created_at' => $time,
                'order_id' => $order['id']
            ];
            $grandAmount = $refereeAmount = 0;
            $channelAmount = round($settle['channel_rate'] * $order['commission_fee'],2);
            $channelTotalAmount += $channelAmount;
            if($order['referee_id']){
                $memberNum ++;
                $refereeAmount = round($settle['referee_rate'] * $order['commission_fee'],2);
                $refereeTotalAmount += $refereeAmount;
                $userTotalAmount += $refereeAmount;
                $details[] = array_merge($detail,[
                    'type' => 2,
                    'user_id' => $order['referee_id'],
                    'amount' => $refereeAmount,
                    'descr' => '推荐分佣'
                ]);
            }
            if($order['grand_id']){
                $memberNum ++;
                $grandAmount = round($settle['grand_rate'] * $order['commission_fee'],2);
                $grandTotalAmount += $grandAmount;
                $userTotalAmount += $grandAmount;
                $details[] = array_merge($detail,[
                    'type' => 3,
                    'user_id' => $order['grand_id'],
                    'amount' => $grandAmount,
                    'descr' => '推荐人推荐分佣'
                ]);
            }
            $userAmount = $order['commission_fee'] - $channelAmount - $refereeAmount - $grandAmount;
            $userTotalAmount += $userAmount;
            $details[] = array_merge($detail,[
                'type' => 1,
                'user_id' => $order['user_id'],
                'amount' => $userAmount,
                'descr' => '分享下单成功分佣'
            ]);
        }
        $detailModel = M(self::T_DETAIL);
        M()->startTrans();
        //生成分佣明细
        $res = $detailModel->addAll($details);
        if(!$res){
            M()->rollback();
            showError(20001,'分佣明细添加失败');
        }
        //更新订单状态
        $orderId = array_column($orders,'id');
        $res = $orderModel->where(['id' => ['in',$orderId]])->setField('state',3);
        if(!$res){
            M()->rollback();
            showError(20002,'订单状态修改失败');
        }
        //更新结算单信息
        $res = $settleModel->where(['id' => $id])->save([
            'total_amount'   => $settle['total_amount'] + $totalAmount,
            'channel_amount' => $settle['channel_amount'] + $channelTotalAmount,
            'grand_amount'   => $settle['grand_amount'] + $grandTotalAmount,
            'referee_amount' => $settle['referee_amount'] + $refereeTotalAmount,
            'member_amount'  => $settle['member_amount'] + $userTotalAmount,
            'member_num'     => $settle['member_num'] + $memberNum,
        ]);
        if(!$res){
            M()->rollback();
            showError(20002,'结算单信息修改失败');
        }
        M()->commit();
        returnResult();
    }

    public function pay(){
        $id = I('post.id');
        if(!$id)
            showError(10006);
        $settleModel = M(self::T_SETTLE);
        $settle = $settleModel->where(['id' => $id,'state'=> 2])->find();
        if(!$settle)
            showError(20000,'结算单不存在');
        $detailModel = M(self::T_DETAIL);
        $details = $detailModel->where([
            'settle_id' => $id,
            'state' => 1
        ])->limit(100)->select();
        if(!$details){
            //用户佣金发放完毕
            M()->startTrans();
            //发放渠道收入
            $res = M(self::T_CHANNEL)->where(['id'=>$settle['channel_id']])
                ->setInc('total_income',$settle['real_amount']);
            if(!$res){
                M()->rollback();
                showError(20001,'渠道总收入更新失败');
            }
            //修改结算单状态
            $res = $settleModel->where(['id' => $id])->save([
                'state' => 3,'pay_time' => time()
            ]);
            if(!$res){
                M()->rollback();
                showError(20001,'结算单状态修改失败');
            }
            M()->commit();
            S('settle_lock_'.$settle['channel_id'],null);
            showError(20004,'暂无未发放的佣金');
        }
        //分佣锁 以免分佣时用户资金操作导致数据错误
        //其他资金操作之前 都应检查此锁是否存在
        $payLock = S('settle_lock_'.$settle['channel_id']);
        if($payLock){
            if($payLock != $id)
                showError(20000,'其他结算正在发放中');
        }else{
            S('settle_lock_'.$settle['channel_id'],$id);
        }
        //获取用户信息
        $memberModel = M(self::T_MEMBER);
        $memberId = array_unique(array_column($details,'user_id'));
        $members = $memberModel->field('id,available_fund')->where(['id' => ['in',$memberId]])->select();
        $members = array_column($members,null,'id');
        //生成用户资金信息和用户资金明细
        $flow = [];
        $time = time();
        foreach($details as $k => $detail){
            if(isset($members[$detail['user_id']])){
                $members[$detail['user_id']]['available_fund'] += $detail['amount'];
                $flow[] = [
                    'user_id'    => $detail['user_id'],
                    'amount'     => $detail['amount'],
                    'balance'    => $members[$detail['user_id']]['available_fund'],
                    'type'       => 1,
                    'note'       => $detail['descr'],
                    'created_at' => $time
                ];
            }else{
                unset($details[$k]);
            }
        }
        $flowModel = M(self::T_FLOW);

        M()->startTrans();
        $res = saveAll($members,self::T_MEMBER);
        if(!$res){
            M()->rollback();
            showError(20002,'用户余额更新失败');
        }
        $res = $flowModel->addAll($flow);
        if(!$res){
            M()->rollback();
            showError(20001,'用户资金明细创建失败');
        }
        $detailId = array_column($details,'id');
        $res = $detailModel->where(['id'=>['in',$detailId]])->setField('state',2);
        if(!$res){
            M()->rollback();
            showError(20002,'分佣明细状态修改失败');
        }
        M()->commit();
        returnResult();
    }

    public function detail(){
        if(IS_POST){
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'settle_id'   => [['num'],true],
                'type'        => [['in' => [1,2]],false,true],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ]);

            $list = M(self::T_DETAIL)->where($where)->page($pageNo,$pageSize)->select();
            $orders = $members = [];
            if($list){
                $userId = array_unique(array_column($list,'user_id'));
                $members = M(self::T_MEMBER)->field('id,username')->where(['id'=>['in',$userId]])->select();
                $members = array_column($members,'username','id');
                $orderId = array_unique(array_column($list,'order_id'));
                $orders = M(self::T_ORDER)->field('id,order_sn')->where(['id'=>['in',$orderId]])->select();
                $orders = array_column($orders,'order_sn','id');
            }

            returnResult([
                'list' => handleRecords([
                    'user_id'    => ['array_walk',$members,'user_id_str'],
                    'order_id'   => ['array_walk',$orders,'order_id_str'],
                    'state'      => ['translate','settle_detail_state','state_str'],
                    'type'       => ['translate','settle_type','type_str'],
                    'created_at' => ['time','Y-m-d H:i:s','created_at_str'],
                ],$list),
                'total' =>M(self::T_DETAIL)->where($where)->count()
            ]);
        }else{
            $id = I('get.id');
            $this->assign('settleId',$id);
            $this->display();
        }
    }
}