<?php

namespace Admin\Controller\Goods;

use Admin\Controller\CommonController;

class AttrController extends CommonController
{
    const T_CATE = 'tr_category';

    const T_ATTR = 'tr_attribute';

    public function index(){
        if(IS_POST){
            $model = M(self::T_ATTR);
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'name'        => [[],false,true,['like','name']],
                'cate_id'     => [['num']],
                'state'       => [['in'=>[1,2]],false,true,['eq','state']],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ]);
            $list = $model->where($where)->page($pageNo,$pageSize)->select();
            $cate = [];
            if($list){
                $cateId = array_filter(array_unique(array_column($list,'cate_id')),function($var){
                    return $var ? 1 : 0;
                });
                if($cateId){
                    $cateModel = M(self::T_CATE);
                    $cate = $cateModel->where(['id'=>['in',$cateId]])->select();
                }
                if($cate)
                    $cate = array_column($cate,'name','id');
            }
            returnResult([
                'list' => handleRecords([
                    'cate_id'    => ['array_walk',$cate,'cate_id_str'],
                    'attr_index' => ['translate','attr_index','attr_index_str'],
                    'input_type' => ['translate','input_type','input_type_str'],
                    'state'      => ['translate','state','state_str'],
                    'created_at' => ['time','Y-m-d H:i:s','created_at_str'],
                ],$list),
                'total' => $model->where($where)->count()
            ]);
        }else{
            $model = M(self::T_CATE);
            $data = $model->field('id,name,pid')->where(['state'=>1])->select();
            $parent = cateFormat($data);
            $this->assign('parent',$parent);
            $this->display();
        }
    }

    public function add(){
        $model = M(self::T_ATTR);
        $rule = [
            'name'        => [[],true],
            'cate_id'     => [['num'],true],
            'attr_index'  => [['in'=>[0,1]]],
            'input_type'  => [['in'=>[0,1,2]]],
            'input_value' => [],
        ];
        $data = validate($rule);
        if(!is_array($data))
            showError(10006);//参数错误

        $record = $model->where([
            'name'    => $data['name'],
            'cate_id' => $data['cate_id'],
        ])->find();
        if($record && $record['id'] != $data['id'])
            showError(20000);

        if(isset($data['input_type']) && $data['input_type'] == 1){
            if(isset($data['input_type']) && $data['input_type']){
                $data['input_value'] = str_replace("\r\n",",",trim($data['input_value']));
            }else{
                showError(10006,'请输入选择项');
            }
        }

        $data['created_at'] = time();
        $insertId = $model->add($data);
        if(!$insertId)
            showError(20001);//创建失败
        returnResult();
    }

    public function edit(){
        $model = M(self::T_ATTR);
        if(IS_POST){
            $rule = [
                'id'          => [['num'],true,false],
                'name'        => [],
                'cate_id'     => [['num']],
                'attr_index'  => [['in'=>[0,1]]],
                'input_type'  => [['in'=>[0,1,2]]],
                'input_value' => [],
            ];
            $data = validate($rule);
            if(!is_array($data))
                showError(10006);//参数错误

            $attr = $model->where(['id' => $data['id']])->find();
            if(!$attr)
                showError(20004);//不存在
            if(isset($data['name']) || isset($data['cate_id'])){
                $name = isset($data['name']) ? $data['name'] : $attr['name'];
                $cateId = isset($data['cate_id']) ? $data['cate_id'] : $attr['cate_id'];
                $record = $model->where([
                    'name'    => $name,
                    'cate_id' => $cateId,
                ])->find();
                if($record && $record['id'] != $data['id'])
                    showError(20000);
            }

            if(isset($data['input_type']) && $data['input_type'] == 1){
                if(isset($data['input_type']) && $data['input_type']){
                    $data['input_value'] = str_replace("\r\n",",",trim($data['input_value']));
                }else{
                    showError(10006,'请输入选择项');
                }
            }
            var_dump($data);die;
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

        $model = M(self::T_ATTR);
        $res = $model->where(['id'=>['in',$data['id']]])->setField('state',3);
        if($res === false)
            showError(20002);
        returnResult();
    }
}