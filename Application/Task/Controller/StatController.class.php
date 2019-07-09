<?php

namespace Task\Controller;

use Common\Consts\Scheme;

class StatController extends CommonController
{

    public function day_stat(){
        $log = 'stat';
        writeLog('统计开始',$log,'DEBUG');
        echo 'stat start'.PHP_EOL;
        //初始化日期
        $startTime = strtotime(date('Y-m-d',strtotime('-1 day')));
        $endTime = strtotime(date('Y-m-d'));
        $where = ['created_at' => ['between',[$startTime,$endTime]]];
        echo 'start time'.$startTime.PHP_EOL;
        echo 'end time'.$endTime.PHP_EOL;
        //统计订单数量
        $orderNum = M(Scheme::COMMISSION)->where($where)->count();
        echo 'order num :'.$orderNum.PHP_EOL;
        //统计预估佣金
        $commission = M(Scheme::C_DETAIL)->where($where)->sum('amount');
        if(!$commission)
            $commission = 0;
        echo 'commission amount: '.$commission.PHP_EOL;
        //统计新增用户数
        $memberNum = M(Scheme::USER)->where($where)->count();
        echo 'new member num : '.$memberNum.PHP_EOL;
        //统计新增运营商数量
        $serviceNum = M(Scheme::U_LEVEL)->where([
            'state' => 2,
            'up_time' => ['between',[$startTime,$endTime]],
        ])->count();
        echo 'new service num : '.$serviceNum.PHP_EOL;
        //统计提现金额
        $withdraw = M(Scheme::U_WITHDRAW)->where([
            'state' => 2,
            'audit_time' => ['between',[$startTime,$endTime]],
        ])->count();
        echo 'withdraw amount: '.$withdraw.PHP_EOL;
        $res = M(Scheme::STAT)->add([
            'order_num' => $orderNum,
            'member_num' => $memberNum,
            'service_num' => $serviceNum,
            'pre_commission' => $commission,
            'withdraw_amount' => $withdraw,
            'created_at' => time(),
        ]);
        if(!$res){
            writeLog('统计数据创建失败',$log,'ERROR');
        }
        writeLog('统计结束',$log,'DEBUG');
        die('stat success'.PHP_EOL);
    }

}