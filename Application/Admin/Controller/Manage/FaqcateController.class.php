<?php

namespace Admin\Controller\Manage;

use Admin\Controller\CommonController;

class FaqcateController extends CommonController
{

    const T_GUIDE_CATE = 'tr_manage_faq_cate';

    public function index(){
        if(IS_POST){
            list($where,$pageNo,$pageSize) = before_query([
                'name'        => [[],false,true,['like','name']],
                'state'       => [['in'=>[1,2]],false,true,['eq','state']],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ]);

            $model = M(self::T_GUIDE_CATE);
            $list = $model->where($where)->page($pageNo,$pageSize)->select();

            returnResult([
                'list' => handleRecords([
                    'state'           => ['translate','state','state_str'],
                    'created_at'      => ['time','Y-m-d H:i:s','created_at_str'],
                ],$list),
                'total' => $model->where($where)->count()
            ]);
        }else{
            $this->display();
        }
    }

    public function add(){
        $model = M(self::T_GUIDE_CATE);
        $rule = [
            'name' => [[],true],
            'sort' => [['num']]
        ];
        $data = beforeSave($model,$rule,['name']);
        $data['created_at'] = time();
        $insertId = $model->add($data);
        if(!$insertId)
            showError(20001);//创建失败
        returnResult();
    }

    public function edit(){
        $model = M(self::T_GUIDE_CATE);
        if(IS_POST){
            $rule = [
                'id'    => [['num'],true,false],
                'name'  => [],
                'sort' => [['num']],
                'state' => [['in'=>[1,2,3]]]
            ];
            $data = beforeSave($model,$rule,['name']);
            $cate = $model->where(['id'=>$data['id']])->find();
            if(!$cate)
                showError(20004);//管理员不存在
            if($model->save($data) === false)
                showError(20002);//更新失败
            returnResult();
        }else{
            $id = I('get.id');
            $user = $model->where('id ='.$id)->find();
            if(!$user)
                showError(20004);//不存在
            returnResult($user);
        }
    }

    public function del(){
        $rule = ['id' => [[],true,false]];
        $data = validate($rule);
        if(!is_array($data))
            showError(10006);//参数错误

        $model = M(self::T_GUIDE_CATE);
        $res = $model->where(['id'=>['in',$data['id']]])->setField('state',3);
        if($res === false)
            showError(20002);
        returnResult();
    }
}