<?php
/**
 * Created by PhpStorm.
 * User: leasin
 * Date: 2019/1/14
 * Time: 16:04
 */

namespace Admin\Controller\System;

use Admin\Controller\CommonController;

class RoleController extends CommonController
{
    const T_ROLE = 'tr_sys_role';

    const T_NODE = 'tr_sys_node';

    public function index(){
        if(IS_POST){
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

            if(!isset($where['id']))
                $where['id'] = ['neq',1];
            else if($where['id'] == 1)
                returnResult(['list'=>[],'total'=>0]);

            if(!isset($where['state']))
                $where['state'] = ['neq',3];
            $pageNo = $where['page_no'];
            unset($where['page_no']);
            $pageSize = $where['page_size'] > 1000 ? 1000 : $where['page_size'];
            unset($where['page_size']);
            $model = M(self::T_ROLE);
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

    public function edit(){
        $model = M(self::T_ROLE);
        if(IS_POST){
            $id = I('post.id');
            if($id){
                //非超级管理员不能编辑超级管理员
                if($id == 1 && $_SESSION['userInfo']['id'] != 1)
                    showError(10110);
                $user = $model->where('id ='.$id)->find();
                if(!$user)
                    showError(20004);//不存在
                $rule = [
                    'name'  => [],
                    'menu'  => [[],false,true,['eq','role_menu']],
                    'state' => [['in'=>[1,2,3]]]
                ];
            }else{
                $rule = [
                    'name' => [[],true],
                    'menu' => [[],false,false,['eq','role_menu']],
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
                $data['created_at'] = time();
                $insertId = $model->add($data);
                if(!$insertId)
                    showError(20001);//创建失败
            }

            returnResult();
        }else{
            $id = I('get.id');
            if($id == 1 && $_SESSION['userInfo']['id'] != 1)
                showError(10110);
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

        $model = M(self::T_ROLE);
        $res = $model->where(['id'=>['in',$data['id']]])->setField('state',3);
        if($res === false)
            showError(20002);
        returnResult();
    }

    public function access(){
        $roleModel = M(self::T_ROLE);
        if(IS_POST){
            $rule = ['id' => [[],true,false]];
            $data = validate($rule);
            if(!is_array($data))
                showError(10006);//参数错误
            $role = $roleModel->where([
                'id' => $data['id'],
                'state' => 1
            ])->find();
            if(!$role)
                showError(20004);
        }else{
            $id = I('get.id');
            if(!$id)
                showError(10006);//参数错误
            $role = $roleModel->where([
                'id' => $id,
                'state' => 1
            ])->find();
            if(!$role)
                showError(20004);
            $nodeModel = M(self::T_NODE);
            $nodes = $nodeModel->select();
        }
    }
}