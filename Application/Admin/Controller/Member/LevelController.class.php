<?php

namespace Admin\Controller\Member;

use Admin\Controller\CommonController;
use Common\Consts\Scheme;


class LevelController extends CommonController
{

    public function index(){
        if(IS_POST){
            $model = M(Scheme::U_LEVEL);
            $list = $model->select();
            returnResult([
                'list' => handleRecords([
                    'state'           => ['translate','state','state_str'],
                ],$list),
                'total' =>$model->count()
            ]);
        }else{
            $this->display();
        }
    }

    public function edit(){
        $model = M(Scheme::U_LEVEL);
        if(IS_POST){
            $rule = [
                'id'    => [['num']],
                'name'  => [],
                'rate'  => [['num']],
                'state' => [['in'=>[1,2]]],
            ];
            $data = beforeSave($model,$rule,[]);
            $level = $model->where(['id'=>$data['id']])->find();
            if(!$level)
                showError(20004);//不存在
            if($model->save($data) === false)
                showError(20002);//更新失败
            returnResult();
        }else{
            $id = I('get.id');
            $level = $model->where('id ='.$id)->find();
            if(!$level)
                showError(20004);//不存在
            returnResult($level);
        }
    }
}