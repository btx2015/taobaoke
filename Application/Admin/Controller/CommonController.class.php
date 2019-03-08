<?php
/**
 * Created by PhpStorm.
 * User: leasin
 * Date: 2019/1/14
 * Time: 14:39
 */

namespace Admin\Controller;

use Think\Controller;


class CommonController extends Controller
{

    public function _initialize()
    {

        if(empty($_SESSION['adminInfo']['id']))
            $this->redirect('System/Login/login');

        $lastLoginTime = strtotime($_SESSION['adminInfo']['last_login_time']);
        if(time() - $lastLoginTime > 3600)
            $this->redirect('System/Login/login');

        if(!checkAccess()){
            if(IS_POST)
                showError(10010);
            else
                $this->redirect('System/Login/login');
        }

    }

}