<?php

namespace Admin\Controller\Commission;

use Admin\Controller\CommonController;
use Common\Consts\Scheme;

class SettlementController extends CommonController
{

    public function index(){
        $state = C('translate')['settle_state'];
        if(IS_POST){
            $state = array_keys($state);
            $model = M(Scheme::SETTLE);
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'settle_sn'   => [[],false,true,['like','settlement_sn']],
                'state'       => [['in'=>$state],false,false,['eq','state']],
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
            $this->assign('state',$state);
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

            $cmd = C('CLI_CMD').' Settle/settle/id/'.$id.' >/dev/null & 2>&1';
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
            $res = $settleModel->where(['id' => $id])->save([
                'pay_time' => time(),
                'state' => 5
            ]);
            if(!$res){
                showError(20001);
            }
            $cmd = C('CLI_CMD').' Pay/pay/id/'.$id.' >/dev/null & 2>&1';
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
                ->field('a.*,s.settlement_sn,o.trade_id,u.username,u.phone')
                ->join('left join '.Scheme::SETTLE.' s on a.settle_id = s.id')
                ->join('left join '.Scheme::S_ORDER.' o on a.order_id = o.id')
                ->join('left join '.Scheme::USER.' u on a.member_id = u.id')
                ->where($where)->page($pageNo,$pageSize)->select();

            returnResult([
                'list' => handleRecords([
                    'state'      => ['translate','settle_detail_state','state_str'],
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

    public function partner(){
        if(IS_POST){
            $id = I('post.id');
            if(!$id)
                showError(10006);
            $settleModel = M(Scheme::SETTLE);
            $settle = $settleModel->where(['id' => $id,'state' => 2])->find();
            if(!$settle)
                showError(20004,'结算单不存在');
            $res = M(Scheme::P_SETTLE)->add([
                'settle_id' => $id,
                'created_at' => time()
            ]);
            if(!$res){
                showError(20001);
            }

            $cmd = C('CLI_CMD').' Settle/settle_partner/id/'.$id.' >/dev/null & 2>&1';
            exec($cmd,$log,$state);
            if($state != 0)
                writeLog(json_encode($log),'exec','ERROR');
        }else{
            $id = I('get.id');
            if(!$id)
                showError(10006);
            $settleModel = M(Scheme::P_SETTLE);
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
}