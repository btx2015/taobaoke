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

    public $roleId;

    public $path;

    public $basicInfo;

    public function _initialize()
    {
        $this->path = strtolower(CONTROLLER_NAME.'/'.ACTION_NAME);

        $this->getBasic();

        if(!$this->needLogin()){
            $this->checkLogin();
            if(!$this->checkAccess()){
                if(IS_POST)
                    showError(10010);
                else{
                    unset($_SESSION['adminInfo']);
                    $this->redirect('System/Public/login');
                }
            }
        }
    }

    private function getBasic(){
        $basic = S('basic_info');
        if(!$basic){
            $basic = M('tr_sys_basic')
                ->where('id=1')->find();
            S('basic_info',$basic);
        }
        $this->basicInfo = $basic;
    }

    private function needLogin(){
        $access = C('ADMIN_ACCESS');
        return isset($access[$this->path]);
    }

    private function checkLogin(){
        if(!isset($_SESSION['adminInfo']))
            $this->redirect('System/Public/login');

        $loginTime = $_SESSION['adminInfo']['login_time'];
        if(time() - $loginTime > $this->basicInfo['login_overtime']*60)
            $this->redirect('System/Public/login');

        $this->roleId = $_SESSION['adminInfo']['role_id'];
    }

    private function checkAccess(){
        $accessData = S('role_access_'.$this->roleId);
        if(!isset($accessData[$this->path]))
            return false;
        $path = $this->path;
        if($accessData[$this->path]['type'] != 1)
            $path = strtolower(CONTROLLER_NAME.'/index');
        $active[$accessData[$path]['id']] = 1;
        if($accessData[$path]['pid'])
            $active[$accessData[$path]['pid']] = 1;
        $menuData = S('role_menu_'.$this->roleId);
        $buttons = S('role_button_'.$this->roleId);
        $buttonData = @$buttons[$path];
        $title = $this->basicInfo ? $this->basicInfo['system_name'] : '管理后台';
        $this->assign([
            'title'      => $title,
            'active'     => $active,
            'menuData'   => $menuData,
            'buttonData' => $buttonData
        ]);
        return true;
    }

    /**
     * 获取菜单，按钮，权限数据
     * @param int $roleId
     * @return bool
     */
    protected function getNodeData($roleId = 0){
        $modelFlag = false;
        $cacheData = [
            'menu'   => [],
            'button' => [],
            'access' => []
        ];
        foreach ($cacheData as $k => $v){
            $cache = S('role_'.$k.'_'.$roleId);
            if(!$cache){
                $modelFlag = true;
                break;
            }
        }
        if($modelFlag){
            $where = ['state' => 1];
            if($roleId != 1){
                $roleModel = M('tr_sys_role');
                $roleNode = $roleModel->where("id=".$roleId)->getField('access_node');
                $roleNode = explode(',',$roleNode);
                $where['id'] = ['in',$roleNode];
            }
            $nodeModel = M('tr_sys_node');
            $nodeData = $nodeModel->field('id,name,path,pid,type,title')
                ->where($where)->order('sort desc')->select();
            if($nodeData){
                list($menuData,$buttonData) = $this->formatNode($nodeData);
                $accessData = array_column($nodeData,null,'path');
                unset($accessData['']);
                $cacheData = [
                    'menu'   => $menuData,
                    'button' => $buttonData,
                    'access' => $accessData
                ];
            }
            foreach($cacheData as $k => $v){
                if(empty($v))
                    return false;
                S('role_'.$k.'_'.$roleId,$v);
            }
        }
        return true;
    }

    /**
     * 递归 格式化 菜单 按钮 数据
     * @param array $nodes
     * @param int $pid
     * @return array
     */
    private function formatNode($nodes = [],$pid = 0){
        $menuData = $buttonData = [];
        foreach($nodes as $key => $node) {
            if ($node['pid'] == $pid) {
                unset($nodes[$key]);
                if ($node['type'] != 1) {
                    $buttonData[$node['name']] = $node;
                    continue;
                } else {
                    list($menuChildren,$buttonChildren) = $this->formatNode($nodes, $node['id']);
                    if ($menuChildren)
                        $node['children'] = $menuChildren;
                    if ($buttonChildren){
                        if($node['path']){
                            $buttonData[$node['path']] = $buttonChildren;
                        }else{
                            $buttonData = array_merge($buttonData,$buttonChildren);
                        }
                    }
                    $menuData[] = $node;
                }
            }
        }
        return [$menuData,$buttonData];
    }
}