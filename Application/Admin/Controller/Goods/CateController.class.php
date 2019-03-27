<?php

namespace Admin\Controller\Goods;

use Admin\Controller\CommonController;

class CateController extends CommonController
{
    
    const T_CATE = 'tr_category';

    public function index(){
        $model = M(self::T_CATE);
        if(IS_POST){
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'name'        => [[],false,true,['like','name']],
                'pid'         => [['num']],
                'state'       => [['in'=>[1,2]],false,true,['eq','state']],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ]);
            $list = $model->where($where)->page($pageNo,$pageSize)->select();
            $parent = [];
            if($list){
                $pid = array_filter(array_unique(array_column($list,'pid')),function($var){
                    return $var ? 1 : 0;
                });
                if($pid)
                    $parent = $model->where(['id'=>['in',$pid]])->select();
                if($parent)
                    $parent = array_column($parent,'name','id');
            }
            returnResult([
                'list' => handleRecords([
                    'pid'        => ['array_walk',$parent,'pid_str'],
                    'state'      => ['translate','state','state_str'],
                    'created_at' => ['time','Y-m-d H:i:s','created_at_str'],
                ],$list),
                'total' => $model->where($where)->count()
            ]);
        }else{
            $data = $model->field('id,name,pid')->where(['state'=>1])->select();
            $parent = cateFormat($data);
            $this->assign('parent',$parent);
            $this->display();
        }
    }

    public function add(){
        $model = M(self::T_CATE);
        $rule = [
            'name' => [[],true],
            'pid'  => [['num']],
            'sort' => [['num']],
        ];
        $data = beforeSave($model,$rule,['name']);
        $data['created_at'] = time();
        $insertId = $model->add($data);
        if(!$insertId)
            showError(20001);//创建失败
        returnResult();
    }

    public function edit(){
        $model = M(self::T_CATE);
        if(IS_POST){
            $rule = [
                'id'    => [['num'],true,false],
                'name'  => [],
                'pid'   => [['num']],
                'sort'  => [['num']],
                'state' => [['in'=>[1,2,3]]]
            ];
            $data = beforeSave($model,$rule,['name']);

            $role = $model->where(['id' => $data['id']])->find();
            if(!$role)
                showError(20004);//不存在
            $res = $model->save($data);

            if($res === false)
                showError(20002);//更新失败

            returnResult();
        }else{
            $id = I('get.id');
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

        $model = M(self::T_CATE);
        $res = $model->where(['id'=>['in',$data['id']]])->setField('state',3);
        if($res === false)
            showError(20002);
        returnResult();
    }

    public function attr(){
        if(IS_POST){
            A('Goods/Attr')->index();
        }else{
            $id = I('get.id');
            if(!$id)
                $this->redirect('Goods/Cate/index');
            $model = M(self::T_CATE);
            $cate = $model->where(['id'=>$id])->find();
            if(!$cate)
                $this->redirect('Goods/Cate/index');
            $this->assign('cid',$id);
            $this->display();
        }
    }
}