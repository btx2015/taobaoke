<?php

namespace Admin\Controller\Commission;

use Admin\Controller\CommonController;
use Common\Consts\Scheme;

class OrderController extends CommonController
{

    public function index(){
        if(IS_POST){
            $model = M(Scheme::S_ORDER);
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
                    $members = M(Scheme::USER)->field('id,username')->where(['id' => ['in',$memberId]])->select();
                    if($members){
                        $members = array_column($members,'username','id');
                    }
                }
            }
            returnResult([
                'list' => handleRecords([
                    'user_id'    => ['array_walk',$members,'user_id_str'],
                    'referee_id' => ['array_walk',$members,'referee_id_str'],
                    'grand_id'   => ['array_walk',$members,'grand_id_str'],
                    'state'      => ['translate','order_state','state_str'],
                ],$list),
                'total' => $model->where($where)->count()
            ]);
        }else{
            $this->display();
        }
    }

    public function edit(){
        if(IS_POST){
            $cd = 'cd /phpstudy/www/trjh.com/ && ';
            $phpPath = '/phpstudy/server/php/bin/php ';
            $func = 'cli.php index index';
            $cmd = $cd.$phpPath.$func.' >/dev/null & 2>&1';
            exec($cmd,$log,$state);
            if($state != 0)
                writeLog(json_encode($log),'exec','DEBUG');
        }else{
            if(S('match_settle_lock'))
                showError(40001);
        }
        returnResult();
    }

}