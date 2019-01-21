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

    public function index(){
        $this->display();
    }

    public function records(){
        $where = validate([
            'usa'       =>  [[],false,true,'like'],
            'phone'     =>  [['phone'],false,true],
            'state'     =>  [['in'=>[1,2]],false,true,'eq'],
            'from_time' =>  [['time'],false,true,['egt','created_at']],
            'to_time'   =>  [['time'],false,true,['elt','created_at']],
        ]);
        if(!is_array($where))
            showError(10006);
        $where['id'] = ['neq',1];
        returnResult([
            'list' => M(self::T_ADMIN)->where($where)->select(),
            'total' =>M(self::T_ADMIN)->where($where)->count()
        ]);

    }

    public function edit(){

    }

    public function save(){
        $id = I('post.id');
        $rule = [
            'email' => [['email']],
            'name'  => []
        ];
        $model = M(self::T_ADMIN);
        if($id){
            $where = 'id ='.$id;
            $user = $model->where($where)->find();
            if(!$user)
                showError(20004);
            $rule = array_merge([
                'usa'     => [],
                'pswd'    => [],
                'phone'   => [['phone']],
                'rold_id' => [['num']],
                'state'   => [['in'=>[1,2,3]]]
            ],$rule);
        }else{
            $rule = array_merge([
                'usa'     => [[],true],
                'pswd'    => [[],true],
                'phone'   => [['phone'],true],
                'rold_id' => [['num'],true]
            ],$rule);
        }
        $data = validate($rule);
        if(!is_array($data))
            showError(10006);

        $field = ['role_id','state'];
        foreach($field as $v)
            if(isset($data[$v]) && $id == $_SESSION['userInfo']['id'])
                showError(10110);

        if(isset($data['usa'])){
            $user = $model->where('usa ='.$data['usa'])->find();
            if($user && $user['id'] != $id)
                showError(20000);
        }

        if($id)
            if($model->where($where)->save($data) === false)
                showError(20002);
        else
            if(!$model->create($data))
                showError(20001);

        returnResult();
    }
}