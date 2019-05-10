<?php
/**
 * Created by PhpStorm.
 * User: leasin
 * Date: 2019/5/10
 * Time: 9:50
 */

namespace Admin\Controller\Manage;

use Admin\Controller\CommonController;

class TaskController extends CommonController
{

    const T_BASIC = 'tr_sys_basic';

    public function index(){
        $model = M(self::T_BASIC);
        $basic = $model->where('config_type = 2 AND state = 1')->order('sort desc')->select();
        array_walk($basic,function(&$v){
            if($v['input_option'])
                $v['input_option'] = json_decode($v['input_option'],true);
        });
        $this->assign('basic',$basic);
        $this->display();
    }

    public function edit(){
        $records = [];
        foreach($_POST as $id => $value){
            $records[] = [
                'id' => $id,
                'config_value' => $value
            ];
        }
        $res = saveAll($records,self::T_BASIC);
        if($res === false)
            showError(20002);
        $basic = M('tr_sys_basic')->where('config_type = 2 AND state = 1')->select();
        $basic = array_column($basic,'config_value','config_name');
        $res = S('task_info',$basic);
        if(!$res)
            showError(20002);
        returnResult();
    }
}