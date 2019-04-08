<?php

namespace Admin\Controller\Commission;

use Admin\Controller\CommonController;

class OrderController extends CommonController
{

    const T_ORDER = 'tr_commission_order';

    const T_MEMBER = 'tr_member';

    const T_CHANNEL = 'tr_channel';

    public function index(){
        $this->display();
    }

    public function get_order(){

    }

    /**
     * @param string $startTime 订单查询开始时间
     * @param int $pageNo 页数
     * @return mixed
     * 接口文档
     * https://developer.alibaba.com/docs/api.htm?spm=a219a.7395905.0.0.29e675fex8tgK4&apiId=24527
     */
    private function api_request($startTime,$pageNo){
        $appSecret = '43967a2c75599e4027f7b5ff698b604b';
        $params = [
            'method' => 'taobao.tbk.order.get',
            'app_key' => '25521171',
            'sign_method' => 'md5',
            'timestamp' => date('Y-m-d H:i:s',time()),
            'format' => 'json',
            'v' => '2.0',
            'fields' => 'trade_id,total_commission_fee,special_id,adzone_id,relation_id',
            'start_time' => $startTime,
            'span' => 1200,
            'page_no' => $pageNo,
            'page_size' => 100,
            'tk_status' => 3,
            'order_query_type' => 'settle_time',
            'order_scene' => 3
        ];
        ksort($params);
        $string = '';
        foreach($params as $k => $v){
            $string .= $k.$v;
        }
        $sign = md5($appSecret.$string.$appSecret);
        $params['sign'] = strtoupper($sign);
        $res = curlRequest('gw.api.taobao.com/router/rest',$params);
        return $res;
    }

    public function sync_info(){

    }
}