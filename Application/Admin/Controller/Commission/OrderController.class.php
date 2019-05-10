<?php

namespace Admin\Controller\Commission;

use Admin\Controller\CommonController;
use Think\Log;

class OrderController extends CommonController
{

    const T_ORDER = 'tr_commission_order';

    const T_MEMBER = 'tr_member';

    const T_CHANNEL = 'tr_channel';

    public function index(){
        if(IS_POST){
            $model = M(self::T_ORDER);
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'order_sn'    => [[],false,true,['like','order_sn']],
                'state'       => [['in'=>[1,2,3]],false,true,['eq','state']],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ],false);
            $list = $model->where($where)->page($pageNo,$pageSize)->order('state asc')->select();
            $members = $channels = [];
            if($list){
                $memberId = [];
                $fields = ['user_id','referee_id','grand_id'];
                foreach($fields as $v){
                    $memberId = array_merge($memberId,array_filter(array_unique(array_column($list,$v)),function($var){
                        return $var ? 1 : 0;
                    }));
                }
                $memberId = array_unique($memberId);
                if($memberId){
                    $members = M(self::T_MEMBER)->field('id,username')->where(['id' => ['in',$memberId]])->select();
                    if($members){
                        $members = array_column($members,'username','id');
                    }
                }
                $channelId = array_column($list,'channel_id');
                $channels = M(self::T_CHANNEL)->field('id,name')->where(['id'=>['in',$channelId]])->select();
                if($channels){
                    $channels = array_column($channels,'name','id');
                }
            }
            returnResult([
                'list' => handleRecords([
                    'user_id'    => ['array_walk',$members,'user_id_str'],
                    'referee_id' => ['array_walk',$members,'referee_id_str'],
                    'grand_id'   => ['array_walk',$members,'grand_id_str'],
                    'channel_id' => ['array_walk',$channels,'channel_id_str'],
                    'state'      => ['translate','order_state','state_str'],
                ],$list),
                'total' => $model->where($where)->count()
            ]);
        }else{
            $this->display();
        }
    }

    public function add(){
        $phpPath = '/phpstudy/server/php/bin/';
        $func = 'php cli.php index index';
        $cmd = $phpPath.$func.' >/dev/null & 2>&1';
        exec($cmd,$log,$state);
    }

}