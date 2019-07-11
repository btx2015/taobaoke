<?php

namespace Admin\Controller\Commission;


use Admin\Controller\CommonController;
use Common\Consts\Scheme;

class PartnerController extends CommonController
{

    public function index(){
        if(IS_POST){
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'id'          => [['num'],false,true,['eq','a.id']],
                'state'       => [['in'=>[0,1,2,4]],false,false,['eq','a.state']],
                'settle_sn'   => [[],false,true,['eq','b.settlement_sn']],
                'create_from' => [['time'],false,true,['egt','a.created_at']],
                'create_to'   => [['time'],false,true,['elt','a.created_at']],
            ],'a');
            $model = M(Scheme::P_SETTLE);
            $list = $model->alias('a')
                ->field('a.*,b.settlement_sn')
                ->join('left join '.Scheme::SETTLE.' b on a.settle_id = b.id')
                ->where($where)->page($pageNo,$pageSize)->select();
            returnResult([
                'list' => handleRecords([
                    'state' => ['translate','partner_settle_state','state_str'],
                    'created_at' => ['time','Y-m-d H:i:s','created_at_str']
                ],$list),
                'total' => $model->alias('a')->where($where)->count()
            ]);
        }else{
            $this->assign('state',C('translate')['partner_settle_state']);
            $this->display();
        }
    }

    public function detail(){
        if(IS_POST){
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'settle_id'   => [['num'],false,true,['eq','a.settle_id']],
                'username'    => [[],false,true,['like','u.username']],
            ]);
            $model = M(Scheme::P_FLOW);
            $list = $model->alias('a')
                ->field('a.*,u.username')
                ->join('left join '.Scheme::USER.' u on a.member_id = u.id')
                ->where($where)->page($pageNo,$pageSize)->select();
            returnResult([
                'list' => handleRecords([
                    'rate' => ['percent',2,'rate_str'],
                    'created_at' => ['time','Y-m-d H:i:s','created_at_str']
                ],$list),
                'total' => $model->alias('a')
                    ->join('left join '.Scheme::USER.' u on a.member_id = u.id')
                    ->where($where)->count()
            ]);
        }else{
            $id = I('get.id');
            $this->assign('settleId',$id);
            $this->display();
        }
    }
}