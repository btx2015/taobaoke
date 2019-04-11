<?php

namespace Admin\Controller\Commission;

use Admin\Controller\CommonController;

class DetailController extends CommonController
{
    const T_SETTLE = 'tr_commission';

    const T_DETAIL = 'tr_commission_detail';

    const T_ORDER = 'tr_commission_order';

    const T_MEMBER = 'tr_member';

    public function index(){
        if(IS_POST){
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'settle_id'   => [],
                'order_id'    => [],
                'user_id'     => [],
                'type'        => [['in' => [1,2,3]]],
                'state'       => [['in' => [1,2]]],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ]);

            if(isset($where['settle_id'])){
                $settleId = M(self::T_SETTLE)->where(['settlement_sn'=>$where['settle_id']])->getField('id');
                if(!$settleId)
                    returnResult(['list'=>[],'total'=>0]);
                $where['settle_id'] = $settleId;
            }
            if(isset($where['order_id'])){
                $settleId = M(self::T_ORDER)->where(['order_sn'=>$where['order_id']])->getField('id');
                if(!$settleId)
                    returnResult(['list'=>[],'total'=>0]);
                $where['order_id'] = $settleId;
            }
            if(isset($where['user_id'])){
                $settleId = M(self::T_MEMBER)->where(['username'=>$where['user_id']])->getField('id');
                if(!$settleId)
                    returnResult(['list'=>[],'total'=>0]);
                $where['user_id'] = $settleId;
            }

            $list = M(self::T_DETAIL)->where($where)->page($pageNo,$pageSize)->select();
            $orders = $members = $settles = [];
            if($list){
                $settleId = array_unique(array_column($list,'settle_id'));
                $settles = M(self::T_SETTLE)->field('id,settlement_sn')->where(['id'=>['in',$settleId]])->select();
                $settles = array_column($settles,'settlement_sn','id');
                $userId = array_unique(array_column($list,'user_id'));
                $members = M(self::T_MEMBER)->field('id,username')->where(['id'=>['in',$userId]])->select();
                $members = array_column($members,'username','id');
                $orderId = array_unique(array_column($list,'order_id'));
                $orders = M(self::T_ORDER)->field('id,order_sn')->where(['id'=>['in',$orderId]])->select();
                $orders = array_column($orders,'order_sn','id');
            }

            returnResult([
                'list' => handleRecords([
                    'settle_id'    => ['array_walk',$settles,'settle_id_str'],
                    'user_id'    => ['array_walk',$members,'user_id_str'],
                    'order_id'   => ['array_walk',$orders,'order_id_str'],
                    'state'      => ['translate','settle_detail_state','state_str'],
                    'type'       => ['translate','settle_type','type_str'],
                    'created_at' => ['time','Y-m-d H:i:s','created_at_str'],
                ],$list),
                'total' =>M(self::T_DETAIL)->where($where)->count()
            ]);
        }else{
            $id = I('get.id');
            $this->assign('settleId',$id);
            $this->display();
        }
    }
}