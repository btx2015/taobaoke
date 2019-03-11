<?php
/**
 * Created by PhpStorm.
 * User: leasin
 * Date: 2019/1/14
 * Time: 14:55
 */

namespace Admin\Controller\System;

use Think\Controller;
use Think\Verify;

class PublicController extends Controller
{

    const T_USER = 'tr_sys_admin';

    public function _initialize()
    {
//        if(check_mysql()){
//            exit();
//        }
    }

    public function login(){
        if(IS_POST){
            $where = validate([
                'usa'     => [[],true,false],
                'captcha' => [[],true,true],
                'pwd'     => [['password'],true,true],
            ]);
            if(!is_array($where))
                showError(10006);

            $verify = new Verify();
            $res = $verify->check($where['captcha']);
            if($res === false)
                showError(30004);

            $adminModel = M(self::T_USER);
            $admin = $adminModel->where([
                'usa'   => $where['usa'],
                'state' => 1
            ])->find();
            if(!$admin || $where['pwd'] !== $admin['pswd'])
                showError(30005);

            getNodeData($admin['role_id']);
            $_SESSION['adminInfo'] = $admin;

            $this->redirect('System/Index');
        }else{
            $this->display();
        }
    }

    public function captcha(){
        $codeSet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQRTUVWXYZ';
        $verify = new Verify([
            'fontSize' => 30,
            'length'   => 4,
            'useNoise' => true,
            'useCurve' => false,
            'codeSet'  => $codeSet,
            'fontttf'  => '4.ttf',
        ]);
        ob_end_clean();
        $verify->entry();
    }
}