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
            $members = [];
            if($list){
                $memberId = array_filter(array_unique(array_column($list,'member_id')),function($var){
                    return $var ? 1 : 0;
                });
                if($memberId){
                    $members = M(Scheme::USER)->field('id,username')->where(['id' => ['in',$memberId]])->select();
                    if($members){
                        $members = array_column($members,'username','id');
                    }
                }
            }
            returnResult([
                'list' => handleRecords([
                    'member_id'  => ['array_walk',$members,'member_id_str'],
                    'state'      => ['translate','order_state','state_str'],
                ],$list),
                'total' => $model->where($where)->count()
            ]);
        }else{
            $this->display();
        }
    }

    public function add(){
        if(S('sync_settle_lock'))
            showError(40001);
        if(IS_POST){
            $cmd = C('CLI_CMD').' Order/sync_settle >/dev/null & 2>&1';
            exec($cmd,$log,$state);
            if($state != 0)
                writeLog(json_encode($log),'exec','ERROR');
        }
        returnResult();
    }

    public function edit(){
        if(S('match_settle_lock'))
            showError(40001);
        if(IS_POST){
            $cmd = C('CLI_CMD').' Match/match_settle >/dev/null & 2>&1';
            exec($cmd,$log,$state);
            if($state != 0)
                writeLog(json_encode($log),'exec','ERROR');
        }
        returnResult();
    }

}