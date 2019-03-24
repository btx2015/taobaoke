<?php

namespace Admin\Controller\Manage;

use Admin\Controller\CommonController;
use Think\Upload;

class NavController extends CommonController
{
    
    const T_NAV = 'tr_manage_nav';

    const SAVE_PATH = '/nav/';

    public function index(){
        if(IS_POST){
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'name'        => [[],false,true,['like','name']],
                'state'       => [['in'=>[1,2]],false,true,['eq','state']],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ]);

            $model = M(self::T_NAV);
            $list = $model->where($where)->page($pageNo,$pageSize)->order('sort desc')->select();

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
        $model = M(self::T_NAV);
        $rule = [
            'name'  => [[],true,false],
            'url'   => [[],true,false],
            'sort'  => [['num']]
        ];
        $data = beforeSave($model,$rule,['name']);
        if(!isset($_FILES['nav_upload']))
            showError(10006,'请上传图片');
        if(!isset($_FILES['nav_upload']['size']) || !$_FILES['nav_upload']['size'])
            showError(10006,'请上传图片');
        $upload = new Upload(); // 实例化上传类
        $upload->savePath = self::SAVE_PATH;// 设置原图上传目录
        $upload->saveName = uniqid();
        $info = $upload->upload();
        if(!$info)
            showError(20002,$upload->getError());
        foreach ($info as $v){
            $data['img'] = '/Uploads'.$v['savepath'].$v['savename'];
        }
        $data['created_at'] = time();
        $insertId = $model->add($data);
        if(!$insertId)
            showError(20001);//创建失败
        returnResult();
    }

    public function edit(){
        $model = M(self::T_NAV);
        if(IS_POST){
            $rule = [
                'id'    => [['num']],
                'name'  => [],
                'url'   => [[],false,true],
                'state' => [['in'=>[1,2,3]]],
                'sort'  => [['num']]
            ];
            $data = beforeSave($model,$rule,['name']);
            $user = $model->where(['id'=>$data['id']])->find();
            if(!$user)
                showError(20004);//不存在

            if(isset($_FILES['nav_upload'])){
                if(isset($_FILES['nav_upload']['size']) && $_FILES['nav_upload']['size']){
                    $upload = new Upload(); // 实例化上传类
                    $upload->savePath = self::SAVE_PATH;// 设置原图上传目录
                    $upload->saveName = uniqid();
                    $info = $upload->upload();
                    if(!$info)
                        showError(20002,$upload->getError());
                    foreach ($info as $v){
                        $data['img'] = '/Uploads'.$v['savepath'].$v['savename'];
                    }
                }
            }

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

        $model = M(self::T_NAV);
        $res = $model->where(['id'=>['in',$data['id']]])->setField('state',3);
        if($res === false)
            showError(20002);
        returnResult();
    }
}