<?php
/**
 * Created by PhpStorm.
 * User: leasin
 * Date: 2019/1/14
 * Time: 14:55
 */

namespace Admin\Controller\System;

use Admin\Controller\CommonController;
use Think\Verify;

class PublicController extends CommonController
{

    const T_USER = 'tr_sys_admin';

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
            if(!$admin)
                showError(30005);

            if($where['pwd'] !== $admin['pswd']){
                $admin['login_error'] = 0;
                $res = $adminModel->where('id = '.$admin['id'])->setInc('login_error');
                if(!$res)
                    showError(30006);
                showError(30005);
            }else{
                $accessCache = S('role_access_'.$admin['role_id']);
                if(!$accessCache)
                    if(!$this->getNodeData($admin['role_id']))
                        showError(30006);

                $_SESSION['adminInfo'] = $admin;

                $admin['last_login_time'] = $admin['login_time'];
                $admin['login_time'] = time();
                $admin['login_num'] = $admin['login_num'] + 1;
                $admin['login_error'] = 0;

                $res = $adminModel->where('id='.$admin['id'])
                    ->save($admin);
                if(!$res)
                    showError(30006);
                returnResult(U('System/Index/index'));
            }
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