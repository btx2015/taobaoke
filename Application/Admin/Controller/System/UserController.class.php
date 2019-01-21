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

    }
}