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
            else{
                unset($_SESSION['adminInfo']);
                $this->redirect('System/Login/login');
            }
        }

        $this->getMenuData();
    }

    private function getMenuData(){
        if(IS_GET){
            $menuData = S('role_menu_1');
            if ($menuData) {
                $active = strtolower(CONTROLLER_NAME . '/' . ACTION_NAME);
                foreach ($menuData as &$v) {
                    if (isset($v['children'])) {
                        foreach ($v['children'] as &$a) {
                            if ($a['path'] === $active) {
                                $a['active'] = 1;
                                $v['active'] = 1;
                                break;
                            }
                        }
                        if (isset($v['active'])) {
                            break;
                        }
                    }
                }
            }
            $this->assign('menuData', $menuData);
        }
    }
}