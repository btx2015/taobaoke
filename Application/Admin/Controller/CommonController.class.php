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

    private $roleId;

    public function _initialize()
    {
        if(empty($_SESSION['adminInfo']))
            $this->redirect('System/Public/login');

        $this->roleId = $_SESSION['adminInfo']['role_id'];

        $loginTime = $_SESSION['adminInfo']['login_time'];
        if(time() - $loginTime > 3600)
            $this->redirect('System/Public/login');
        if(!checkAccess($this->roleId)){
            if(IS_POST)
                showError(10010);
            else{
                unset($_SESSION['adminInfo']);
                $this->redirect('System/Public/login');
            }
        }
        $this->getMenuData();
    }

    private function getMenuData(){
        $active = strtolower(CONTROLLER_NAME . '/' . ACTION_NAME);
        $accessData = S('role_access_'.$this->roleId);
        if($accessData[$active]){
            if(!is_numeric($_SESSION['accessData'][$active]))
                $active = strtolower(CONTROLLER_NAME . '/' . $_SESSION['accessData'][$active]);
            $menuData = S('role_menu_'.$this->roleId);
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
            $buttons = S('role_button_'.$this->roleId);;
            if(isset($buttons[$active]))
                $buttonData = $buttons[$active];
            $this->assign([
                'menuData'   => $menuData,
                'buttonData' => $buttonData
            ]);
        }
    }
}