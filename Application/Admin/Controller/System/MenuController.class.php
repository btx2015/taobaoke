<?php

namespace Admin\Controller\System;

use Admin\Controller\CommonController;

class MenuController extends CommonController
{

    const T_MENU = 'tr_sys_menu';

    const T_BUTTON = 'tr_sys_menu_button';

    public function index(){
        $this->display();
    }

    public function records(){
        $where = validate([
            'page_no'     => [['num'],1],
            'page_size'   => [['num'],10],
            'id'          => [['num'],false,true,['eq','id']],
            'name'        => [[],false,true,['like','name']],
            'state'       => [['in'=>[1,2]],false,true,['eq','state']],
            'create_from' => [['time'],false,true,['egt','created_at']],
            'create_to'   => [['time'],false,true,['elt','created_at']],
        ]);
        if(!is_array($where))
            showError(10006);

        if(!isset($where['state']))
            $where['state'] = ['neq',3];
        $pageNo = $where['page_no'];
        unset($where['page_no']);
        $pageSize = $where['page_size'] > 1000 ? 1000 : $where['page_size'];
        unset($where['page_size']);
        $model = M(self::T_MENU);
        $list = $model->where($where)->page($pageNo,$pageSize)->select();

        returnResult([
            'list' => handleRecords([
                'state'           => ['translate','state','state_str'],
                'created_at'      => ['time','Y-m-d H:i:s','created_at_str'],
            ],$list),
            'total' => $model->where($where)->count()
        ]);
    }

    public function edit(){
        $this->display();
    }

    public function save(){
        $id = I('post.id');
        $model = M(self::T_MENU);
        if($id){
            $user = $model->where('id ='.$id)->find();
            if(!$user)
                showError(20004);//不存在
            $rule = [
                'name'    => [],
                'path'    => [[],false],
                'state'   => [['in'=>[1,2,3]]],
            ];
        }else{
            $rule = [
                'name'    => [[],true],
                'path'    => [[],true,false],
            ];
        }
        $data = validate($rule);
        if(!is_array($data))
            showError(10006);//参数错误

        if(isset($data['name'])){
            $user = $model->where("name ='".$data['name']."'")->find();
            if($user && $user['id'] != $id)
                showError(20000);//存在同名
        }

        if($id){
            if($model->where('id ='.$id)->save($data) === false)
                showError(20002);//更新失败
        }else{
            $insertId = $model->add($data);
            if(!$insertId)
                showError(20001);//创建失败
        }

        returnResult();
    }
}