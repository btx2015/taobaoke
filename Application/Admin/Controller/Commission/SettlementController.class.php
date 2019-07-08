<?php

namespace Admin\Controller\Commission;

use Admin\Controller\CommonController;
use Common\Consts\Scheme;

class SettlementController extends CommonController
{

    public function index(){
        if(IS_POST){
            $model = M(Scheme::SETTLE);
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'settle_sn'   => [[],false,true,['like','settlement_sn']],
                'state'       => [['in'=>[1,2,3]],false,true,['eq','state']],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ],false);
            $list = $model->where($where)->page($pageNo,$pageSize)->select();

            returnResult([
                'list' => handleRecords([
                    'state'       => ['translate','settle_state','state_str'],
                    'settle_time' => ['time','Y-m-d H:i:s','settle_time_str'],
                    'pay_time'    => ['time','Y-m-d H:i:s','pay_time_str'],
                ],$list),
                'total' => $model->where($where)->count()
            ]);
        }else{
            $this->display();
        }
    }

    public function add(){
        $rate = M(Scheme::S_RATE)->where(['state'=>1])->select();
        if(!$rate)
            showError(20001,'分佣比例未设置');

        $rate = array_column($rate,'rate','id');
        $rateInfo = json_encode($rate);
        $insertId = M(Scheme::SETTLE)->add([
            'settlement_sn' => date('YmdHis'),
            'rate_info'     => $rateInfo,
            'created_at'    => time()
        ]);
        if(!$insertId)
            showError(20001);//创建失败
        returnResult();
    }

    public function settle(){
        if(IS_POST){
            $id = I('post.id');
            if(!$id)
                showError(10006);
            $settleModel = M(Scheme::SETTLE);
            $settle = $settleModel->where(['id' => $id,'state' => ['in',[0,4]]])->find();
            if(!$settle)
                showError(20004,'结算单不存在');
            $res = $settleModel->where(['id' => $id])->save([
                'settle_time' => time(),
                'state' => 1
            ]);
            if(!$res){
                showError(20001);
            }
            $cd = 'cd /phpstudy/www/trjh.com/ && ';
            $phpPath = '/phpstudy/server/php/bin/php ';
            $func = 'cli.php Settle/settle/id'.$id;
            $cmd = $cd.$phpPath.$func.' >/dev/null & 2>&1';
            exec($cmd,$log,$state);
            if($state != 0)
                writeLog(json_encode($log),'exec','ERROR');
        }else{
            $id = I('get.id');
            if(!$id)
                showError(10006);
            $settleModel = M(Scheme::SETTLE);
            $settle = $settleModel->where(['id' => $id])->find();
            if(!$settle)
                showError(20004,'结算单不存在');
            if($settle['state'] == 1){
                showError(40002);//结算中
            }else if($settle['state'] == 4){
                showError(40003);//结算失败
            }
        }
        returnResult();
    }

    public function pay(){
        if(IS_POST){
            $id = I('post.id');
            if(!$id)
                showError(10006);
            $settleModel = M(Scheme::SETTLE);
            $settle = $settleModel->where(['id' => $id,'state'=> 2])->find();
            if(!$settle)
                showError(20000,'结算单不存在');
            $cd = 'cd /phpstudy/www/trjh.com/ && ';
            $phpPath = '/phpstudy/server/php/bin/php ';
            $func = 'cli.php Pay/pay/id/'.$id;
            $cmd = $cd.$phpPath.$func.' >/dev/null & 2>&1';
            exec($cmd,$log,$state);
            if($state != 0)
                writeLog(json_encode($log),'exec','ERROR');
        }else{
            $id = I('get.id');
            if(!$id)
                showError(10006);
            $settleModel = M(Scheme::SETTLE);
            $settle = $settleModel->where(['id' => $id])->find();
            if(!$settle)
                showError(20004,'结算单不存在');
            if($settle['state'] == 5){
                showError(40004);//发放中
            }else if($settle['state'] == 7){
                showError(40005);//结算失败
            }
        }
        returnResult();
    }

    public function detail(){
        if(IS_POST){
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'settle_id'   => [['num'],true],
                'type'        => [['in' => [1,2]],false,true],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ]);

            $list = M(Scheme::S_DETAIL)->where($where)->page($pageNo,$pageSize)->select();
            $orders = $members = [];
            if($list){
                $userId = array_unique(array_column($list,'member_id'));
                $members = M(Scheme::USER)->field('id,username')->where(['id'=>['in',$userId]])->select();
                $members = array_column($members,'username','id');
                $orderId = array_unique(array_column($list,'order_id'));
                $orders = M(Scheme::S_ORDER)->field('id,trade_id')->where(['id'=>['in',$orderId]])->select();
                $orders = array_column($orders,'trade_id','id');
            }

            returnResult([
                'list' => handleRecords([
                    'member_id'    => ['array_walk',$members,'member_id_str'],
                    'trade_id'   => ['array_walk',$orders,'trade_id_str'],
                    'state'      => ['translate','settle_detail_state','state_str'],
                    'type'       => ['translate','settle_type','type_str'],
                    'created_at' => ['time','Y-m-d H:i:s','created_at_str'],
                ],$list),
                'total' =>M(Scheme::S_DETAIL)->where($where)->count()
            ]);
        }else{
            $id = I('get.id');
            $this->assign('settleId',$id);
            $this->display();
        }
    }
}