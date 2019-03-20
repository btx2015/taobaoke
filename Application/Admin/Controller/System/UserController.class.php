<?php

namespace Admin\Controller\System;

use Admin\Controller\CommonController;

class UserController extends CommonController
{
    const T_ADMIN = 'tr_sys_admin';

    const T_ROLE = 'tr_sys_role';

    public function index(){
        if(IS_POST){
            $where = validate([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'id'          => [['num'],false,true,['eq','a.id']],
                'usa'         => [[],false,true,['like','a.usa']],
                'name'        => [[],false,true,['like','a.name']],
                'phone'       => [['phone'],false,true,['like','a.phone']],
                'email'       => [['email'],false,true,['like','a.email']],
                'role_id'     => [['num'],false,true,['eq','a.role_id']],
                'state'       => [['in'=>[1,2]],false,true,['eq','a.state']],
                'login_ip'    => [[],false,true,['like','a.login_ip']],
                'create_from' => [['time'],false,true,['egt','a.created_at']],
                'create_to'   => [['time'],false,true,['elt','a.created_at']],
                'login_from'  => [['time'],false,true,['egt','a.login_time']],
                'login_to'    => [['time'],false,true,['elt','a.login_time']],
            ]);
            if(!is_array($where))
                showError(10006);
            if(!isset($where['a.id']))
                $where['a.id'] = ['neq',1];
            else if($where['a.id'] == 1)
                returnResult(['list'=>[],'total'=>0]);

            if(!isset($where['a.state']))
                $where['a.state'] = ['neq',3];
            $pageNo = $where['page'];
            unset($where['page']);
            $pageSize = $where['rows'] > 1000 ? 1000 : $where['rows'];
            unset($where['rows']);
            $list = M(self::T_ADMIN)->alias('a')
                ->join('left join '.self::T_ROLE.' b on a.role_id = b.id')
                ->field('a.*,b.name as role_id_str')
                ->where($where)->page($pageNo,$pageSize)->order('a.id DESC')->select();
            returnResult([
                'list' => handleRecords([
                    'state'           => ['translate','state','state_str'],
                    'created_at'      => ['time','Y-m-d H:i:s','created_at_str'],
                    'login_time'      => ['time','Y-m-d H:i:s','login_time_str'],
                    'last_login_time' => ['time','Y-m-d H:i:s','last_login_time_str'],
                ],$list),
                'total' =>M(self::T_ADMIN)->alias('a')->where($where)->count()
            ]);
        }else{
            $model =  M(self::T_ROLE);
            $roles = $model->field('id,name')->where('id != 1 AND state = 1')->select();
            $this->assign('roleOption',$roles);
            $this->display();
        }
    }

    public function edit(){
        $model = M(self::T_ADMIN);
        if(IS_POST){
            $id = I('post.id');
            $rule = [
                'phone' => [['phone']],
                'name'  => [],
                'email' => [['email']],
            ];
            if($id){
                //非超级管理员不能编辑超级管理员
                if($id == 1 && $_SESSION['userInfo']['id'] != 1)
                    showError(10110);
                $user = $model->where('id ='.$id)->find();
                if(!$user)
                    showError(20004);//管理员不存在
                $rule = array_merge([
                    'usa'     => [],
                    'pswd'    => [['password']],
                    'role_id' => [['num']],
                    'state'   => [['in'=>[1,2]]]
                ],$rule);
            }else{
                $rule = array_merge([
                    'usa'     => [[],true],
                    'pswd'    => [['password'],true],
                    'role_id' => [['num'],true]
                ],$rule);
            }
            $data = validate($rule);
            if(!is_array($data))
                showError(10006);//参数错误

            if(isset($data['usa'])){
                $user = $model->where("usa ='".$data['usa']."'")->find();
                if($user && $user['id'] != $id)
                    showError(20000);//存在同名管理员
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
                showError(20004);//管理员不存在
            returnResult($user);
        }
    }

    public function del(){
        $rule = ['id' => [[],true,false]];
        $data = validate($rule);
        if(!is_array($data))
            showError(10006);//参数错误

        $model = M(self::T_ADMIN);
        $res = $model->where(['id'=>['in',$data['id']]])->setField('state',3);
        if($res === false)
            showError(20002);
        returnResult();
    }

    public function reset(){
        $rule = [
            'old' => [['password'],true,true],
            'new' => [['password'],true,true,['eq','pswd']],
        ];
        $data = validate($rule);
        if(!is_array($data))
            showError(10006);//参数错误
        $model = M(self::T_ADMIN);
        $where = 'id = '.$_SESSION['userInfo']['id'];
        $user = $model->where($where)->find();
        if(!$user)
            showError(20004);//管理员不存在
        if($user['pswd'] != $data['old'])
            showError(30005);//密码错误
        unset($data['old']);
        if($model->where($where)->save($data) === false)
            showError(20002);//更新失败
        returnResult();
    }
}