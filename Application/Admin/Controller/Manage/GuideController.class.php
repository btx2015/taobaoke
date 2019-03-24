<?php

namespace Admin\Controller\Manage;


use Admin\Controller\CommonController;

class GuideController extends CommonController
{

    const T_GUIDE = 'tr_manage_guide';

    public function index(){
        if(IS_POST){
            list($where,$pageNo,$pageSize) = before_query([
                'title'       => [[],false,true,['like','title']],
                'state'       => [['in'=>[1,2]]],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ]);

            $model = M(self::T_GUIDE);
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
        if(IS_POST){
            $model = M(self::T_GUIDE);
            $rule = [
                'title'   => [[],true],
                'sort'    => [['num']],
                'content' => []
            ];
            $data = beforeSave($model,$rule,['title']);
            $data['created_at'] = time();
            $insertId = $model->add($data);
            if(!$insertId)
                showError(20001);
            returnResult();
        }else{
            $this->display();
        }
    }

    public function edit(){
        $model = M(self::T_GUIDE);
        if(IS_POST){
            $rule = [
                'id'      => [['num'],true,false],
                'title'   => [],
                'sort'    => [['num']],
                'state'   => [['in'=>[1,2,3]]],
                'content' => []
            ];
            $data = beforeSave($model,$rule,['title']);
            $cate = $model->where(['id'=>$data['id']])->find();
            if(!$cate)
                showError(20004);
            if($model->save($data) === false)
                showError(20002);
            returnResult();
        }else{
            $id = I('get.id');
            $user = $model->where('id ='.$id)->find();
            if(!$user)
                showError(20004);
            $user['content'] = stripcslashes(htmlspecialchars_decode($user['content']));
            $this->assign('data',$user);
            $this->display();
        }
    }

    public function del(){
        $rule = ['id' => [[],true,false]];
        $data = validate($rule);
        if(!is_array($data))
            showError(10006);

        $model = M(self::T_GUIDE);
        $res = $model->where(['id'=>['in',$data['id']]])->setField('state',3);
        if($res === false)
            showError(20002);
        returnResult();
    }
}