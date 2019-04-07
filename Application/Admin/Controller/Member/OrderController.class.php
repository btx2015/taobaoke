<?php

namespace Admin\Controller\Member;

use Admin\Controller\CommonController;

class OrderController extends CommonController
{

    const T_ORDER = 'tr_member_order';

    public function index(){
        $appSecret = '43967a2c75599e4027f7b5ff698b604b';
        $params = [
            'method' => 'taobao.tbk.order.get',
            'app_key' => '25521171',
            'sign_method' => 'md5',
            'timestamp' => date('Y-m-d H:i:s',time()),
            'format' => 'json',
            'v' => '2.0',
            'fields' => 'trade_id,total_commission_fee,special_id,earning_time',
            'start_time' => '2019-03-20 00:00:00',
        ];
        ksort($params);
        $string = '';
        foreach($params as $k => $v){
            $string .= $k.$v;
        }
        $sign = md5($appSecret.$string.$appSecret);
        $params['sign'] = strtoupper($sign);
        $res = curlRequest('gw.api.taobao.com/router/rest',$params);
        var_dump($res);
    }
}