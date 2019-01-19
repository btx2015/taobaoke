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
            'username'  =>  [[],false,true,'like'],
            'phone'     =>  [['phone'],false,false],
            'status'    =>  [['in'=>[1,2]],false,true,['eq','state']],
            'from_time' =>  [['time'],false,true,['egt','created_at']],
            'to_time'   =>  [['time'],false,true,['elt','created_at']],
        ]);
        if(!is_array($where))
            showError(10006);
        returnResult([
            'list' => M(self::T_ADMIN)->where($where)->find(),
            'total' =>M(self::T_ADMIN)->where($where)->count()
        ]);

    }

    public function edit(){

    }

    public function save(){

    }
}