<?php
/**
 * Created by PhpStorm.
 * User: leasin
 * Date: 2019/5/20
 * Time: 15:21
 */

namespace Task\Controller;

use Common\Consts\Scheme;

class PayController extends CommonController
{

    const LIMIT = 100;

    public function pay(){
        //分佣锁 以免分佣时用户资金操作导致数据错误
        //其他资金操作之前 都应检查此锁是否存在
        $payLock = S('settle_lock');
        if($payLock){
            showError(20000,'其他结算正在发放中');
        }else{
            S('settle_lock',time());
        }
        if(!isset($_GET['id'])){
            writeLog('结算单ID不存在','pay','ERROR');
            exit('Settlement ID is not exits!');
        }
        $id = $_GET['id'];
        $settleModel = M(Scheme::SETTLE);
        $settle = $settleModel->where(['id' => $id,'state'=> 2])->find();
        if(!$settle)
            showError(20000,'结算单不存在');

        $detailModel = M(Scheme::S_DETAIL);
        $memberModel = M(Scheme::USER);
        M()->startTrans();
        while(true){
            $details = $detailModel->where([
                'settle_id' => $id,
                'state' => 1
            ])->limit(self::LIMIT)->select();
            if(!$details){
                break;
            }
            //获取用户信息
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
            $flowModel = M(Scheme::U_FUND_FLOW);

            M()->startTrans();
            $res = saveAll($members,Scheme::USER);
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
        }
        //用户佣金发放完毕
        //发放渠道收入
        $res = M(Scheme::CHANNEL)->where(['id'=>$settle['channel_id']])
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
        M()->commit();
    }
}