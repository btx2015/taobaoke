<?php

namespace Admin\Controller\System;

use Admin\Controller\CommonController;

class RoleController extends CommonController
{
    const T_ROLE = 'tr_sys_role';

    const T_NODE = 'tr_sys_node';

    public function index(){
        if(IS_POST){
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'id'          => [['num'],false,true,['eq','id']],
                'name'        => [[],false,true,['like','name']],
                'state'       => [['in'=>[1,2]],false,true,['eq','state']],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ]);

            if(!isset($where['id']))
                $where['id'] = ['neq',1];
            else if($where['id'] == 1)
                returnResult(['list'=>[],'total'=>0]);

            $model = M(self::T_ROLE);
            $list = $model->where($where)->page($pageNo,$pageSize)->select();

            returnResult([
                'list' => handleRecords([
                    'state'           => ['translate','state','state_str'],
                    'created_at'      => ['time','Y-m-d H:i:s','created_at_str'],
                ],$list),
                'total' => $model->where($where)->count()
            ]);
        }else{
            $this->display();
        }
    }

    public function add(){
        $model = M(self::T_ROLE);
        $rule = [
            'name' => [[],true],
            'menu' => [[],false,false,['eq','role_menu']],
        ];
        $data = beforeSave($model,$rule,['name']);
        $data['created_at'] = time();
        $insertId = $model->add($data);
        if(!$insertId)
            showError(20001);//创建失败
        returnResult();
    }

    public function edit(){
        $model = M(self::T_ROLE);
        if(IS_POST){
            $rule = [
                'id'    => [['num'],true,false],
                'name'  => [],
                'menu'  => [[],false,true,['eq','role_menu']],
                'state' => [['in'=>[1,2,3]]]
            ];
            $data = beforeSave($model,$rule,['name']);
            if($data['id'] == 1 && $_SESSION['userInfo']['id'] != 1)
                showError(10110);
            $role = $model->where(['id' => $data['id']])->find();
            if(!$role)
                showError(20004);//不存在
            $res = $model->save($data);

            if($res === false)
                showError(20002);//更新失败

            returnResult();
        }else{
            $id = I('get.id');
            if($id == 1 && $_SESSION['userInfo']['id'] != 1)
                showError(10110);
            $user = $model->where('id ='.$id)->find();
            if(!$user)
                showError(20004);//不存在
            returnResult($user);
        }
    }

    public function del(){
        $rule = ['id' => [[],true,false]];
        $data = validate($rule);
        if(!is_array($data))
            showError(10006);//参数错误

        $model = M(self::T_ROLE);
        $res = $model->where(['id'=>['in',$data['id']]])->setField('state',3);
        if($res === false)
            showError(20002);
        returnResult();
    }

    public function access(){
        $roleModel = M(self::T_ROLE);
        if(IS_POST){
            $rule = [
                'id'     => [[],true,false],
                'access' => [[],true,false]
            ];
            $data = validate($rule);
            if(!is_array($data))
                showError(10006);//参数错误
            $role = $roleModel->where([
                'id' => $data['id'],
                'state' => 1
            ])->find();
            if(!$role)
                showError(20004);
            if($data['access']){
                sort($data['access']);
                $data['access_node'] = implode(',',$data['access']);
                unset($data['access']);
            }
            $res = $roleModel->where([
                'id' => $data['id']
            ])->save($data);
            if($res === false)
                showError(20002);

            if($this->getNodeData($data['id'],true))
                showError(20002);

            returnResult();
        }else{
            $id = I('get.id');
            if(!$id)
                showError(10006);//参数错误
            $role = $roleModel->where([
                'id' => $id
            ])->find();
            if(!$role)
                showError(20004);
            $nodeModel = M(self::T_NODE);
            $nodes = $nodeModel->field('id,pid,type,title')
                ->where('state = 1')->order('sort desc')->select();
            $checked = explode(',',$role['access_node']);
            $nodeData = $this->formatAccess($nodes,$checked);
            $this->assign([
                'roleInfo' => $role,
                'nodeData' => $nodeData
            ]);
            $this->display();
        }
    }

    private function formatAccess($nodes,$checked,$pid = 0){
        $accessData = [];
        foreach($nodes as $key => $node) {
            if ($node['pid'] == $pid) {
                $access = [
                    'id'   => $node['id'],
                    'name' => $node['title'],
                ];
                if(in_array($node['id'],$checked))
                    $access['checked'] = 1;
                unset($nodes[$key]);
                $accessChildren = $this->formatAccess($nodes,$checked, $node['id']);
                if ($accessChildren)
                    $access['children'] = $accessChildren;
                $accessData[] = $access;
            }
        }
        return $accessData;
    }
}