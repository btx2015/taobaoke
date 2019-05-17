<?php

namespace Task\Controller;

use Common\Consts\Scheme;

class SettleController extends CommonController
{

    public function sync_settle(){
        $model = M(Scheme::SETTLE);
        $where['state'] = 1;
        if(isset($_GET['id'])){
            $where['id'] = $_GET['id'];
            $settle = $model->where($where)->select();
            if(!$settle){
                writeLog('结算单不存在','settle','ERROR');
                exit('Settlement is not exits');
            }
        }else{
            if(isset($_GET['channel']))
                $where['id'] = $_GET['channel'];
            $channels = M(Scheme::CHANNEL)->where($where)->select();
            if(!$channels){
                writeLog('没有渠道可结算','settle','ERROR');
                exit('Channel is not exits');
            }
            $settle = [];
            $prefix = date('Ymd');
            $time = time();
            foreach($channels as $channel){
                $settle[] = [
                    'settlement_sn'=> $prefix.uniqid(),
                    'channel_id'   => $channel['id'],
                    'fee_rate'     => $channel['fee_rate'],
                    'channel_rate' => $channel['channel_rate'],
                    'referee_rate' => $channel['referee_rate'],
                    'grand_rate'   => $channel['grand_rate'],
                    'created_at'   => $time
                ];
            }
            $insertId = $model->addAll($settle);
        }

    }

    private function sync_order_v1($params){
        $log = 'settle.order';
        writeLog('订单同步开始',$log,'DEBUG');
        $basic = S('basic_info');
        if(!isset($basic['tbk_app_key']) || $basic['tbk_app_key']
            || !isset($basic['tbk_app_secret']) || $basic['tbk_app_secret']){
            writeLog('淘宝客配置错误',$log,'ERROR');
            return false;
        }
        $time = strtotime(date('Y-m-d H:i'));
        $startTime = date('Y-m-d H:i:s',$time);
        $params = [
            'method' => 'taobao.tbk.order.get',
            'fields' => 'trade_id,total_commission_fee,special_id,relation_id',
            'start_time' => $startTime,
            'span' => 1200,
            'page_size' => 100,
            'tk_status' => 3,
            'order_query_type' => 'settle_time',
            'order_scene' => 3
        ];
        $pageNo = 1;
        $total = 0;
        Vendor('Taobaoke');
        $tbk = new \Taobaoke($basic['tbk_app_key'],$basic['tbk_app_secret']);
        while(true){
            $params['page_no'] = $pageNo;
            $res = $tbk->request($params);
            if($res['result'] === 'Y'){
                if(empty($data)){
                    $info = '订单同步完成。同步订单数量：'.$total;
                    writeLog($info,$log,'DEBUG');
                    return false;
                }else{
                    $insertId = $this->add_data($res['data']);
                    if(!$insertId){
                        $error = '订单保存失败。请求参数：'.json_encode($params);
                        writeLog($error,$log,'ERROR');
                        return false;
                    }
                    $total += count($res['data']);
                    $pageNo ++;
                }
            }else{
                $error = '淘宝客接口请求失败。请求参数：'.json_encode($params).'；返回结果：'.$res['msg'];
                writeLog($error,$log,'ERROR');
                return false;
            }
        }
        return true;
    }

    private function add_data($data){
        $model = M(Scheme::SETTLE);
        $time = time();
        array_walk($data,function(&$v) use($time){
            $v['created_at'] = $time;
        });
        $insertId = $model->addAll($data);
        return $insertId;
    }

    public function matching(){
        $log = 'order.match';
        writeLog('开始匹配用户',$log,'DEBUG');
        $model = M(Scheme::COMMISSION);
        $orders = $model->field('id,special_id')->limit(100)->where('state = 0')->select();
        if(!$orders){
            writeLog('暂无未匹配的订单',$log,'DEBUG');
            return true;
        }
        $memberId = array_unique(array_column($orders,'special_id'));
        $memberModel = M(Scheme::USER);
        $members = $memberModel->field('id,special_id,referee_id,channel_id')
            ->where(['special_id' => ['in',$memberId]])->select();
        if(!$members){
            writeLog('未找到会员',$log,'DEBUG');
            return true;
        }
        //查询二级推荐人
        $refereeId = array_unique(array_column($members,'referee_id'));
        $referees = $memberModel->field('id,referee_id')
            ->where(['id' => ['in',$refereeId]])->select();
        $referees = array_column($referees,'referee_id','id');

        $members = array_column($members,null,'special_id');
        //组装用户信息
        array_walk($orders,function(&$v)use($members,$referees){
            if(isset($members[$v['special_id']])){
                $v['user_id'] = $members[$v['special_id']]['id'];
                $v['referee_id'] = $members[$v['special_id']]['referee_id'];
                $v['channel_id'] = $members[$v['special_id']]['channel_id'];
                if(isset($referees[$v['referee_id']])){
                    $v['grand_id'] = $referees[$v['referee_id']];
                }else{
                    $v['grand_id'] = 0;
                }
                $v['state'] = 1;
            }
        });
        $res = saveAll($orders,Scheme::COMMISSION);
        if(!$res){
            writeLog('数据保存失败，原因：'.$res,$log,'ERROR');
            return false;
        }
        writeLog('匹配完成',$log,'DEBUG');
        return true;
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
}