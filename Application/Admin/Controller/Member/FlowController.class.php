<?php

namespace Admin\Controller\Member;


use Admin\Controller\CommonController;

class FlowController extends CommonController
{

    const T_FLOW = 'tr_member_fund_flow';

    const T_MEMBER = 'tr_member';

    public function index(){
        if(IS_POST){
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'username'    => [[],false,true,['like','b.username']],
                'phone'       => [[],false,true,['like','b.phone']],
                'type'        => [[],false,true,['eq','a.type']],
                'create_from' => [['time'],false,true,['egt','a.created_at']],
                'create_to'   => [['time'],false,true,['elt','a.created_at']],
            ],false);

            $list = M(self::T_FLOW)->alias('a')
                ->join('left join '.self::T_MEMBER.' b on a.user_id = b.id')
                ->field('a.*,b.username,b.phone')
                ->where($where)->page($pageNo,$pageSize)->select();

            returnResult([
                'list' => handleRecords([
                    'type'       => ['translate','flow_type','type_str'],
                    'created_at' => ['time','Y-m-d H:i:s','created_at_str'],
                ],$list),
                'total' =>M(self::T_FLOW)->alias('a')
                    ->join('left join '.self::T_MEMBER.' b on a.user_id = b.id')
                    ->where($where)->count()
            ]);
        }else{
            $this->display();
        }
    }
}