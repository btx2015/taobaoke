<?php

namespace Task\Controller;

use Common\Consts\Scheme;

class SettleController extends CommonController
{

    const LIMIT = 100;//每次结算订单数量

    const SETTLE_LOCK = 'settle_lock';//结算锁

    public function create(){
        $log = 'settle.create';
        $lock = S(self::SETTLE_LOCK);
        $time = time();
        if($lock){
            writeLog('正在结算',$log,'ERROR');
            exit('Settling now ! please wait');
        }
        S(self::SETTLE_LOCK,$time);
        $model = M(Scheme::SETTLE);
        if(isset($_GET['id'])){//结算单ID
            $where['state'] = ['in',[1,4]];
            $where['id'] = $_GET['id'];
            $settle = $model->where($where)->find();
            if(!$settle){
                writeLog('结算单不存在',$log,'ERROR');
                exit('Settlement is not exits');
            }
        }else{
            $where['state'] = 1;
            if(isset($_GET['channel']))//渠道ID
                $where['id'] = $_GET['channel'];
            $channels = M(Scheme::CHANNEL)->where($where)->select();
            if(!$channels){
                writeLog('没有渠道可结算',$log,'ERROR');
                exit('Channel is not exits');
            }
            $settles = [];
            $prefix = date('Ymd');
            foreach($channels as $channel){
                $settles[] = [
                    'settlement_sn'=> $prefix.uniqid(),
                    'channel_id'   => $channel['id'],
                    'fee_rate'     => $channel['fee_rate'],
                    'channel_rate' => $channel['channel_rate'],
                    'referee_rate' => $channel['referee_rate'],
                    'grand_rate'   => $channel['grand_rate'],
                    'created_at'   => $time
                ];
            }
            $insertId = $model->addAll($settles);
            if(!$insertId){
                writeLog('结算单创建失败',$log,'ERROR');
                exit('Settlement created failed');
            }
            $count = count($settles);
            writeLog('结算单创建完成。结算单数量：'.$count,$log,'DEBUG');
            echo 'Settlement created success.Total:'.$count.PHP_EOL;
            $insertId = $insertId - count($settles) + 1;
            array_walk($settles,function(&$v)use(&$insertId){
                $v['id'] = $insertId;
                $insertId ++;
            });
        }
        writeLog('结算开始',$log,'DEBUG');
        echo 'Settlement Start.'.PHP_EOL;
        $total = count($settles);
        $success = 0;
        foreach($settles as $settle){
            $res = $this->start($settle);
            if(!$res){
                $result = $model->where(['id'=>$settle['id']])->save(['state'=>4]);
                writeLog('结算单'.$settle['id'].'结算失败',$log,'ERROR');
                echo 'Settlement:'.$settle['id'].' settle failed'.PHP_EOL;
                if($result === false){
                    writeLog('结算单'.$settle['id'].'结算失败状态修改失败',$log,'ERROR');
                    echo 'Settlement:'.$settle['id'].' updated failed'.PHP_EOL;
                }
                continue;
            }
            $success ++;
        }
        writeLog('结算结束',$log,'DEBUG');
        echo 'Settlement Complete.Total:'.$total.';Success:'.$success.PHP_EOL;
        S(self::SETTLE_LOCK,null);
        return true;
    }

    private function start($settle){
        $log = 'settle.start';
        if(!isset($settle['id'])){
            writeLog('结算单ID不存在。结算单：'.json_encode($settle),$log,'ERROR');
            echo 'Settlement ID is not exits.';
            return false;
        }
        $settleModel = M(Scheme::SETTLE);
        $settle = $settleModel->where(['id' => $settle['id'],'state' => 1])->find();
        if(!$settle){
            writeLog('结算单不存在。结算单：'.json_encode($settle),$log,'ERROR');
            echo 'Settlement is not exits';
            return false;
        }
        writeLog('结算单ID：'.$settle['id'].'开始结算。',$log,'DEBUG');
        echo 'Settlement:'.$settle['id'].' start.'.PHP_EOL;
        $orderModel = M(Scheme::S_ORDER);
        $detailModel = M(Scheme::S_DETAIL);
        $page = 1;
        $totalAmount = 0;//总佣金
        $channelTotalAmount = 0;//渠道收入
        $grandTotalAmount = 0;//二级推荐人收入
        $refereeTotalAmount = 0;//一级推荐人收入
        $userTotalAmount = 0;//用户收入
        $memberNum = 0;//参与分佣用户数量
        $orderIds = [];//订单ID
        M()->startTrans();
        while(true){
            $orders = $orderModel->where([
                'channel_id' => $settle['channel_id'],
                'state'      => 2
            ])->limit(self::LIMIT)->page($page)->select();
            if(!$orders){
                writeLog('暂无订单未结算。',$log,'DEBUG');
                echo 'No orders to settle'.PHP_EOL;
                break;
            }
            $details = [];
            $time = time();
            //遍历订单 计算佣金
            foreach($orders as $order){
                $totalAmount += $order['commission_fee'];
                $memberNum ++;
                $detail = [
                    'settle_id' => $settle['id'],
                    'created_at' => $time,
                    'order_id' => $order['id']
                ];
                $grandAmount = $refereeAmount = 0;
                $channelAmount = round($settle['channel_rate'] * $order['total_commission_fee'],2);
                $channelTotalAmount += $channelAmount;
                if($order['referee_id']){
                    $memberNum ++;
                    $refereeAmount = round($settle['referee_rate'] * $order['total_commission_fee'],2);
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
                    $grandAmount = round($settle['grand_rate'] * $order['total_commission_fee'],2);
                    $grandTotalAmount += $grandAmount;
                    $userTotalAmount += $grandAmount;
                    $details[] = array_merge($detail,[
                        'type' => 3,
                        'user_id' => $order['grand_id'],
                        'amount' => $grandAmount,
                        'descr' => '推荐人推荐分佣'
                    ]);
                }
                $userAmount = $order['total_commission_fee'] - $channelAmount - $refereeAmount - $grandAmount;
                $userTotalAmount += $userAmount;
                $details[] = array_merge($detail,[
                    'type' => 1,
                    'user_id' => $order['user_id'],
                    'amount' => $userAmount,
                    'descr' => '分享下单成功分佣'
                ]);
                $orderIds[] = $order['id'];
            }
            //生成分佣明细
            $res = $detailModel->addAll($details);
            if(!$res){
                M()->rollback();
                writeLog('分佣明细添加失败',$log,'ERROR');
                echo 'Commission details created failed'.PHP_EOL;
                return false;
            }
            $page ++;
        }
        //批量更新订单状态
        if($orderIds){
            $res = $orderModel->where(['id' => ['in',$orderIds]])->setField('state',3);
            if($res === false){
                M()->rollback();
                writeLog('订单状态修改失败',$log,'ERROR');
                echo 'Orders state updated failed'.PHP_EOL;
                return false;
            }
        }
        //更新结算单信息
        $fee = round($totalAmount * $settle['channel_rate'],2);
        $realAmount = $totalAmount - $fee;
        $res = $settleModel->where(['id' => $settle['id']])->save([
            'total_amount'   => $settle['total_amount'] + $totalAmount,
            'channel_amount' => $settle['channel_amount'] + $channelTotalAmount,
            'grand_amount'   => $settle['grand_amount'] + $grandTotalAmount,
            'referee_amount' => $settle['referee_amount'] + $refereeTotalAmount,
            'member_amount'  => $settle['member_amount'] + $userTotalAmount,
            'member_num'     => $settle['member_num'] + $memberNum,
            'fee_amount'     => $fee,
            'real_amount'    => $realAmount,
            'state'          => 2,
            'settle_time'    => time()
        ]);
        if(!$res){
            M()->rollback();
            writeLog('结算单信息修改失败',$log,'ERROR');
            echo 'Settlement updated failed'.PHP_EOL;
            return false;
        }
        M()->commit();
        writeLog('结算单ID：'.$settle['id'].'结算完成',$log,'DEBUG');
        echo 'Settlement:'.$settle['id'].' settle complete'.PHP_EOL;
        return true;
    }
}