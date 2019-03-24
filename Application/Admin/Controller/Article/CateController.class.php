<?php

namespace Admin\Controller\Article;

use Admin\Controller\CommonController;

class CateController extends CommonController
{

    const T_ARTICLE_CATE = 'tr_article_cate';

    public function index(){
        if(IS_POST){
            list($where,$pageNo,$pageSize) = before_query([
                'name'        => [[],false,true,['like','name']],
                'pid'         => [['num']],
                'state'       => [['in'=>[1,2]]],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ]);

            $model = M(self::T_ARTICLE_CATE);
            $list = $model->where($where)->page($pageNo,$pageSize)->select();
            if($list){
                $pid = array_filter(array_unique(array_column($list,'pid')),function($val){
                    return $val ? 1 : 0;
                });

                $parent = $model->field('id,name')->where(['id'=>['in',$pid]])->select();

                $parent = array_column($parent,'name','id');

                array_walk($list,function(&$v) use($parent){
                    $v['pid_str'] = isset($parent[$v['pid']]) ? $parent[$v['pid']] : '-';
                });
            }

            returnResult([
                'list' => handleRecords([
                    'state'           => ['translate','state','state_str'],
                    'created_at'      => ['time','Y-m-d H:i:s','created_at_str'],
                ],$list),
                'total' => $model->where($where)->count()
            ]);
        }else{
            $model = M(self::T_ARTICLE_CATE);
            $cate = $model->field('id,name,pid')
                ->where(['state' => 1])->select();
            $data = $this->cateFormat($cate);
            $this->assign('cateOption',$data);
            $this->display();
        }
    }

    public function add(){
        $model = M(self::T_ARTICLE_CATE);
        $rule = [
            'name' => [[],true],
            'sort' => [['num']],
            'pid'  => [['num']]
        ];
        $data = beforeSave($model,$rule,['name']);
        $data['created_at'] = time();
        $insertId = $model->add($data);
        if(!$insertId)
            showError(20001);//创建失败
        returnResult();
    }

    private function cateFormat($cate,$pid = 0,$level = 0){
        $data = [];
        foreach($cate as $k => $v){
            if($v['pid'] == $pid){
                $name = '';
                if($pid)
                    $name = '|';
                for($i = 0;$i < $level;$i ++){
                    $name .= '-';
                }
                $data[] = [
                    'id'   => $v['id'],
                    'name' => $name.$v['name'],
                ];
                unset($cate[$k]);
                $children = $this->cateFormat($cate,$v['id'],$level+1);
                if($children)
                    $data = array_merge($data,$children);
            }
        }
        return $data;
    }

    public function edit(){
        $model = M(self::T_ARTICLE_CATE);
        if(IS_POST){
            $rule = [
                'id'    => [['num'],true,false],
                'name'  => [],
                'pid'   => [['num']],
                'sort'  => [['num']],
                'state' => [['in'=>[1,2,3]]]
            ];
            $data = beforeSave($model,$rule,['name']);
            $cate = $model->where(['id'=>$data['id']])->find();
            if(!$cate)
                showError(20004);//管理员不存在
            if($model->save($data) === false)
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

        $model = M(self::T_ARTICLE_CATE);
        $res = $model->where(['id'=>['in',$data['id']]])->setField('state',3);
        if($res === false)
            showError(20002);
        returnResult();
    }
}