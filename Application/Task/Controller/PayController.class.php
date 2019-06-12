<?php

namespace Task\Controller;

use Common\Consts\Scheme;
use Think\Exception;

class PayController extends CommonController
{

    const LIMIT = 100;

    const PAY_LOCK = 'pay_lock';

    public function pay(){
        //分佣锁 以免分佣时用户资金操作导致数据错误
        //其他资金操作之前 都应检查此锁是否存在
        $payLock = S(self::PAY_LOCK);
        if($payLock){
            writeLog('正在发放中','pay','ERROR');
            exit('Paying now! Please wait');
        }
        S(self::PAY_LOCK,time());
        if(!isset($_GET['id'])){
            writeLog('结算单ID不存在','pay','ERROR');
            exit('Settlement ID is not exits!');
        }
        $id = $_GET['id'];
        $settleModel = M(Scheme::SETTLE);
        $settle = $settleModel->where(['id' => $id,'state'=> 2])->find();
        if(!$settle){
            writeLog('结算单不存在','pay','ERROR');
            exit('Settlement is not exits!');
        }
        writeLog('结算单'.$id.'开始发放','pay','DEBUG');
        try{
            $detailModel = M(Scheme::S_DETAIL);
            $memberModel = M(Scheme::USER);
            $memberInfo = $detailId = $flow = [];
            $run = true;
            $page = 1;
            M()->startTrans();
            while($run){
                echo 'Page:'.$page.PHP_EOL;
                $details = $detailModel->where([
                    'settle_id' => $id,
                    'state' => 1
                ])->limit(self::LIMIT)->page($page)->select();
                if(!$details){
                    writeLog('暂无结算详情','pay','DEBUG');
                    break;
                }
                //获取用户信息
                $memberId = array_unique(array_column($details,'user_id'));
                $members = $memberModel->field('id,available_fund')->where(['id' => ['in',$memberId]])->select();
                $members = array_column($members,null,'id');
                //生成用户资金信息和用户资金明细
                $flow = [];
                //计算佣金
                foreach($details as $k => $detail){
                    if(isset($members[$detail['user_id']])){
                        if(isset($memberInfo[$detail['user_id']])){
                            $memberInfo[$detail['user_id']]['available_fund'] += $detail['amount'];
                            $memberInfo[$detail['user_id']]['last_settle'] += $detail['amount'];
                        }else{
                            $members[$detail['user_id']]['available_fund'] += $detail['amount'];
                            $members[$detail['user_id']]['last_settle'] = $detail['amount'];
                            $memberInfo[$detail['user_id']] = $members[$detail['user_id']];
                        }
                    }else{
                        unset($details[$k]);
                        continue;
                    }
                }

                $detailId = array_merge($detailId,array_column($details,'id'));
                $page ++;
            }

            if($memberInfo){
                //生成用户资金明细
                writeLog('开始生成用户资金明细','pay','DEBUG');
                $time = time();
                foreach($memberInfo as $member){
                    $flow[] = [
                        'user_id'    => $member['id'],
                        'amount'     => $member['last_settle'],
                        'balance'    => $member['available_fund'],
                        'type'       => 1,
                        'note'       => '结算收入',
                        'created_at' => $time
                    ];
                }
                $flowModel = M(Scheme::U_FUND_FLOW);
                $res = $flowModel->addAll($flow);
                if(!$res){
                    $run = false;
                    writeLog('用户资金明细生成失败','pay','ERROR');
                }
                //修改用户余额和上月结算金额
                if($run){
                    writeLog('修改用户余额和上月结算金额','pay','DEBUG');
                    $res = saveAll($memberInfo,Scheme::USER);
                    if(!$res){
                        $run = false;
                        writeLog('用户余额更新失败','pay','ERROR');
                    }
                }
            }
            //修改分佣明细状态
            if($run && $detailId){
                writeLog('修改分佣明细状态','pay','DEBUG');
                $res = $detailModel->where(['id'=>['in',$detailId]])->setField('state',2);
                if(!$res) {
                    $run = false;
                    writeLog('分佣明细状态修改失败','pay','ERROR');
                }
            }
            //发放渠道收入
            if($run){
                writeLog('发放渠道收入','pay','DEBUG');
                $res = M(Scheme::CHANNEL)->where(['id'=>$settle['channel_id']])
                    ->setInc('total_income',$settle['real_amount']);
                if(!$res){
                    $run = false;
                    writeLog('渠道总收入更新失败','pay','ERROR');
                }
            }
            //修改结算单状态
            if($run){
                writeLog('修改结算单状态','pay','DEBUG');
                $res = $settleModel->where(['id' => $id])->save([
                    'state' => 4,'pay_time' => time()
                ]);
                if(!$res){
                    $run = false;
                    writeLog('结算单状态修改失败','pay','ERROR');
                }
            }
            if($run){
                M()->commit();
                writeLog('发放完成','pay','DEBUG');
            }else{
                M()->rollback();
                writeLog('发放失败','pay','DEBUG');
                $this->pay_fail($id);
            }
            S(self::PAY_LOCK,null);
            return $run;
        }catch (Exception $exception){
            writeLog('佣金发放异常。原因：'.$exception->getMessage(),'pay','ERROR');
            return false;
        }
    }

    private function pay_fail($settle_id){
        $settleModel = M(Scheme::SETTLE);
        writeLog('结算单'.$settle_id.'佣金发放失败','pay','ERROR');
        //发放失败
        $res = $settleModel->where(['id' => $settle_id])->save([
            'state' => 5,'pay_time' => time()
        ]);
        if(!$res){
            writeLog('结算单状态修改失败','pay','ERROR');
        }
    }
}