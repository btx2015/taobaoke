<?php
/**
 * Created by PhpStorm.
 * User: leasin
 * Date: 2019/1/14
 * Time: 16:03
 */

namespace Admin\Controller\System;

use Admin\Controller\CommonController;

class UserController extends CommonController
{
    const T_ADMIN = 'sys_admin';

    const T_ROLE = 'sys_role';

    public function index(){
        $this->display();
    }

    public function records(){
        $where = validate([
            'page_no'     => [['num'],1],
            'page_size'   => [['num'],10],
            'id'          => [['num'],false,true,['eq','a.id']],
            'usa'         => [[],false,true,['like','a.usa']],
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
        $pageNo = $where['page_no'];
        unset($where['page_no']);
        $pageSize = $where['page_size'] > 1000 ? 1000 : $where['page_size'];
        unset($where['page_size']);
        $list = M(self::T_ADMIN)->alias('a')
            ->join('left join sys_role b on a.role_id = b.id')
            ->field('a.*,b.name as role_id_str')
            ->where($where)->page($pageNo,$pageSize)->select();
        returnResult([
            'list' => handleRecords([
                'state'           => ['translate','state','state_str'],
                'created_at'      => ['time','Y-m-d H:i:s','created_at_str'],
                'login_time'      => ['time','Y-m-d H:i:s','login_time_str'],
                'last_login_time' => ['time','Y-m-d H:i:s','last_login_time_str'],
            ],$list),
            'total' =>M(self::T_ADMIN)->alias('a')->where($where)->count()
        ]);
    }

    public function edit(){

    }

    public function save(){
        $id = I('post.id');
        $rule = [
            'phone' => [['phone']],
            'name'  => [],
            'email' => [['email']],
        ];
        $model = M(self::T_ADMIN);
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
                'state'   => [['in'=>[1,2,3]]]
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
            $insertId = $model->add($data);
            if(!$insertId)
                showError(20001);//创建失败
        }

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