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
        $_SESSION['adminInfo']['role_id'] = 1;
//        if(empty($_SESSION['adminInfo']['id']))
//            $this->redirect('System/Login/login');
//
//        $lastLoginTime = strtotime($_SESSION['adminInfo']['last_login_time']);
//        if(time() - $lastLoginTime > 3600)
//            $this->redirect('System/Login/login');
//
//        if(!checkAccess()){
//            if(IS_POST)
//                showError(10010);
//            else{
//                unset($_SESSION['adminInfo']);
//                $this->redirect('System/Login/login');
//            }
//        }
        getNodeData(1);
        $this->getMenuData();
    }

    private function getMenuData(){
        if(ACTION_NAME === 'index'){
            $active = strtolower(CONTROLLER_NAME . '/' . ACTION_NAME);
            $menuData = S('role_menu_'.$_SESSION['adminInfo']['role_id']);
            if ($menuData) {
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
            }else{
                $menuData = [];
            }

            $buttonData = [];
            $buttons = S('role_button_'.$_SESSION['adminInfo']['role_id']);
            if(isset($buttons[$active]))
                $buttonData = $buttons[$active];
            $this->assign([
                'menuData'   => $menuData,
                'buttonData' => $buttonData
            ]);
        }
    }
}