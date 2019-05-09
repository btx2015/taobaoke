<?php

namespace Admin\Controller\System;

use Admin\Controller\CommonController;

class BasicController extends CommonController
{

    const T_BASIC = 'tr_sys_basic';

    public function index(){
        $model = M(self::T_BASIC);
        $basic = $model->where('config_type = 1 AND state = 1')->order('sort desc')->select();
        array_walk($basic,function(&$v){
            if($v['input_option'])
                $v['input_option'] = json_decode($v['input_option'],true);
        });
        $this->assign('basic',$basic);
        $this->display();
    }

    public function edit(){
        $update = validate([
            'system_name'    => [[]],
            'system_domain'  => [[]],
            'system_run'     => [['in' => [0,1]]],
            'login_error'    => [['num']],
            'login_overtime' => [['num']],
        ]);
        if(!is_array($update))
            showError(10006);
        $model = M(self::T_BASIC);
        $res = $model->where('id=1')->save($update);
        if($res === false)
            showError(20002);
        $basic = $model->where('id=1')->find();
        $res = S('basic_info',$basic);
        if(!$res)
            showError(20002);
        returnResult();
    }
}