<?php

namespace Admin\Controller\Channel;

use Admin\Controller\CommonController;

class InfoController extends CommonController
{
    
    const T_CHANNEL = 'tr_channel';

    public function index(){
        if(IS_POST){
            $model = M(self::T_CHANNEL);
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'name'        => [[],false,true,['like','name']],
                'state'       => [['in'=>[1,2]],false,true,['eq','state']],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ]);
            $list = $model->where($where)->page($pageNo,$pageSize)->select();

            returnResult([
                'list' => handleRecords([
                    'channel_rate'  => ['percent',2,'channel_rate_str'],
                    'fee_rate'      => ['percent',2,'fee_rate_str'],
                    'referee_rate'  => ['percent',2,'referee_rate_str'],
                    'grand_rate'    => ['percent',2,'grand_rate_str'],
                    'state'         => ['translate','state','state_str'],
                    'created_at'    => ['time','Y-m-d H:i:s','created_at_str'],
                ],$list),
                'total' => $model->where($where)->count()
            ]);
        }else{
            $this->display();
        }
    }

    public function add(){
        $model = M(self::T_CHANNEL);
        $rule = [
            'name'          => [[],true],
            'pid'           => [[],true],
            'fee_rate'      => [['num','percent'],true],
            'referee_rate'  => [['num','percent'],true],
            'grand_rate'    => [['num','percent'],true],
        ];

        $data = beforeSave($model,$rule,['name']);

        $data['created_at'] = time();
        $insertId = $model->add($data);
        if(!$insertId)
            showError(20001);//创建失败
        returnResult();
    }

    public function edit(){
        $model = M(self::T_CHANNEL);
        if(IS_POST){
            $rule = [
                'id'            => [['num'],true,false],
                'name'          => [],
                'pid'           => [],
                'fee_rate'      => [['num','percent']],
                'referee_rate'  => [['num','percent']],
                'grand_rate'    => [['num','percent']],
                'state'         => [['in' => [1,2]]]
            ];
            $data = beforeSave($model,$rule,['id','name']);
            $res = $model->save($data);
            if($res === false)
                showError(20002);//更新失败

            returnResult();
        }else{
            $id = I('get.id');
            $user = $model->where('id ='.$id)->find();
            if(!$user)
                showError(20004);//不存在
            $user['fee_rate'] = ($user['fee_rate']*100);
            $user['referee_rate'] = ($user['referee_rate']*100);
            $user['grand_rate'] = ($user['grand_rate']*100);
            $user['channel_rate'] = ($user['channel_rate']*100);
            returnResult($user);
        }
    }

    public function del(){
        $rule = ['id' => [[],true,false]];
        $data = validate($rule);
        if(!is_array($data))
            showError(10006);//参数错误
        if($data['id'] == 1){//自营渠道不能删除
            showError(10006,'自营渠道不能删除');//参数错误
        }
        $model = M(self::T_CHANNEL);
        $res = $model->where(['id'=>['in',$data['id']]])->setField('state',3);
        if($res === false)
            showError(20002);
        returnResult();
    }
    
}