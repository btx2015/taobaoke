<?php

namespace Admin\Controller\Commission;

use Admin\Controller\CommonController;
use Common\Consts\Scheme;

class DetailController extends CommonController
{

    public function index(){
        if(IS_POST){
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'settle_id'   => [[],false,true,['eq','a.settle_id']],
                'order_id'    => [[],false,true,['eq','a.order_id']],
                'member_id'   => [[],false,true,['eq','a.member_id']],
                'settlement_sn' => [[],false,true,['eq','s.settlement_sn']],
                'trade_id'    => [[],false,true,['eq','o.trade_id']],
                'username'    => [[],false,true,['eq','u.username']],
                'type'        => [['in' => [1,2,3,4]]],
                'state'       => [['in' => [1,2]]],
                'create_from' => [['time'],false,true,['egt','a.created_at']],
                'create_to'   => [['time'],false,true,['elt','a.created_at']],
            ],'a');
            $model = M(Scheme::S_DETAIL);
            $list = $model->alias('a')
                ->field('a.*,s.settlement_sn,o.trade_id,u.username,u.phone,u.level')
                ->join('left join '.Scheme::SETTLE.' s on a.settle_id = s.id')
                ->join('left join '.Scheme::S_ORDER.' o on a.order_id = o.id')
                ->join('left join '.Scheme::USER.' u on a.member_id = u.id')
                ->where($where)->page($pageNo,$pageSize)->select();

            returnResult([
                'list' => handleRecords([
                    'state'      => ['translate','settle_detail_state','state_str'],
                    'level'      => ['translate','member_level','level_str'],
                    'type'       => ['translate','settle_type','type_str'],
                    'created_at' => ['time','Y-m-d H:i:s','created_at_str'],
                ],$list),
                'total' => $model->alias('a')->where($where)->count()
            ]);
        }else{
            $id = I('get.id');
            $this->assign('settleId',$id);
            $this->display();
        }
    }
}