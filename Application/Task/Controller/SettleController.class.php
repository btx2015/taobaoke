<?php

namespace Task\Controller;

use Common\Consts\Scheme;

class SettleController extends CommonController
{

    const LIMIT = 100;//每次结算订单数量

    const SETTLE_LOCK = 'settle_lock';//结算锁

    public $type = [1 => '自购分佣',2 => '分享分佣',3 => '推荐分佣',4 => '运营商分佣'];

    public function settle(){
        S(self::SETTLE_LOCK,null);
        $log = 'settle.create';

        if(!isset($_GET['id'])){//结算单ID
            writeLog('结算单id不存在',$log,'ERROR');
            exit('Settlement ID is not exits !');
        }

        $lock = S(self::SETTLE_LOCK);
        $time = time();
        if($lock){
            writeLog('正在结算',$log,'ERROR');
            exit('Settling now ! please wait');
        }
        S(self::SETTLE_LOCK,$time);

        $where['state'] = ['in',[1,4]];
        $insertId = $where['id'] = $_GET['id'];
        $settleModel = M(Scheme::SETTLE);
        $settle = $settleModel->where($where)->find();
        if(!$settle){
            writeLog('结算单不存在',$log,'ERROR');
            exit('Settlement is not exits');
        }

        writeLog('结算开始',$log,'DEBUG');
        echo 'Settlement Start.'.PHP_EOL;

        $orderModel = M(Scheme::S_ORDER);
        //查询所有下单用户
        $orderMembers = $orderModel
            ->distinct(true)
            ->field('member_id')
            ->where(['state' => 2])
            ->select();
        if(!$orderMembers){
            writeLog('暂无订单未结算。',$log,'DEBUG');
            echo 'No orders to settle'.PHP_EOL;
        }

        //获取上个月升级用户
        $startTime = strtotime(date('Y-m-01', strtotime('-1 month')));
        $endTime = strtotime(date('Y-m-t', strtotime('-1 month')));
        $levelModel = M(Scheme::U_LEVEL);
        $levelUpMembers = $levelModel
            ->where([
                'up_time' => ['between',$startTime,$endTime],
                'state' => 2
            ])->order('up_time desc')->select();
        $levelUp = [];
        if($levelUpMembers){
            foreach($levelUpMembers as $levelUpMember){
                $levelUp[$levelUpMember['member_id']][] = $levelUpMember;
            }
        }

        $logic = D('Settle','Logic');
        $memberModel = M(Scheme::USER);
        $detailModel = M(Scheme::S_DETAIL);
        $page = 1;
        $totalAmount = 0;//总佣金
        $payAmount = 0;//用户分佣金额
        $orderIds = [];//订单
        $payInfo = [];//用户分佣金额统计
        $detailInfo = [];//分佣明细
        $pay = [
            'settle_id'  => $insertId,
            'created_at' => $time
        ];
        $rateInfo = json_decode($settle['rate_info'],true);

        M()->startTrans();
        foreach($orderMembers as $orderMember){
            //查询用户
            $member = $memberModel
                ->field('id,level,referee_id,referee_map')
                ->where(['id' => $orderMember['member_id']])
                ->find();
            $refereeMap = explode(',',$member['referee_map']);
            //查询推荐体系
            $refereeInfo = M(Scheme::USER)
                ->field('id,level,referee_id,partner_id')
                ->where(['id' => ['in',$refereeMap]])
                ->order('id desc')
                ->select();
            $refereeInfo = array_column($refereeInfo,null,'id');
            //查询合伙人
            $partner = $logic->getPartner($refereeInfo);
            while(true){
                echo $page.PHP_EOL;
                $orders = $orderModel
                    ->where(['member_id'=>$member['id'],'state' => 2])
                    ->limit(self::LIMIT)
                    ->page($page)
                    ->select();
                if(!$orders){
                    writeLog($member['id'].'用户订单结算完成。',$log,'DEBUG');
                    echo 'No orders to settle'.PHP_EOL;
                    S(self::SETTLE_LOCK,null);
                    break;
                }
                //遍历订单
                foreach($orders as $order){
                    $orderIds[] = $order['id'];
                    $totalAmount += $order['commission_fee'];
                    //获取分佣对象 计算佣金
                    $objects = $logic->getObjects($member,$refereeInfo,$order,$levelUp);
                    $amounts = $logic->cal($objects,$rateInfo,$order);
                    $detail = [
                        'settle_id'  => $insertId,
                        'order_id'   => $order['id'],
                        'created_at' => $time
                    ];
                    foreach($amounts as $amount){
                        $payAmount += $amount['amount'];
                        //生成结算明细
                        $amount['partner_id'] = $partner;
                        $amount['descr'] = $this->type[$amount['type']];
                        $detailInfo[] = array_merge($detail,$amount);
                        //修改用户分佣金额
                        if(isset($payInfo[$amount['member_id']])){
                            $payInfo[$amount['member_id']]['amount'] = $payInfo[$amount['member_id']]['amount'] + $amount['amount'];
                        }else{
                            $payInfo[$amount['member_id']] = array_merge($pay,[
                                'member_id' => $amount['member_id'],
                                'amount' => $amount['amount']
                            ]);
                        }
                    }
                }
                $page ++;
            }
        }
        //生成分佣明细
        if($detailInfo){
            $res = $detailModel->addAll($detailInfo);
            if(!$res){
                M()->rollback();
                writeLog('分佣明细添加失败',$log,'ERROR');
                echo 'Commission details created failed'.PHP_EOL;
                $this->failed($insertId);
            }
        }

        //生成分佣金额记录
        if($payInfo){
            $payModel = M(Scheme::S_PAY);
            $payInfo = array_values($payInfo);
            $res = $payModel->addAll($payInfo);
            if(!$res){
                M()->rollback();
                writeLog('分佣金额记录添加失败',$log,'ERROR');
                echo 'Commission payInfo created failed'.PHP_EOL;
                $this->failed($insertId);
            }
        }

        //批量更新订单状态
        if($orderIds){
            $res = $orderModel->where(['id' => ['in',$orderIds]])->setField('state',3);
            if($res === false){
                M()->rollback();
                writeLog('订单状态修改失败',$log,'ERROR');
                echo 'Orders state updated failed'.PHP_EOL;
                $this->failed($insertId);
            }
        }

        //更新结算单信息
        $res = $settleModel->where(['id' => $settle['id']])->save([
            'total_amount' => $totalAmount,
            'pay_amount'   => $payAmount,
            'member_num'   => count($payInfo),
            'state'        => 2,
            'settle_time'  => $time
        ]);
        if(!$res){
            M()->rollback();
            writeLog('结算单信息修改失败',$log,'ERROR');
            echo 'Settlement updated failed'.PHP_EOL;
            $this->failed($insertId);
        }

        M()->commit();

        writeLog('结算结束',$log,'DEBUG');
        echo 'Settlement Complete.'.PHP_EOL;

        return true;
    }

    private function failed($settleId){
        $log = 'settle.failed';
        writeLog('结算失败',$log,'ERROR');
        $res = M(Scheme::SETTLE)->where(['id' => $settleId])->save(['state'=>4]);
        S(self::SETTLE_LOCK,null);
        if($res === false){
            writeLog('结算失败状态修改失败',$log,'ERROR');
            exit('Settlement updated failed'.PHP_EOL);
        }
        exit('Settlement failed'.PHP_EOL);
    }
}