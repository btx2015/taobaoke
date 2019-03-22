<?php

namespace Admin\Controller\Manage;

use Admin\Controller\CommonController;
use Think\Upload;

class BannerController extends CommonController
{

    const T_BANNER = "tr_manage_banner";

    public function index(){
        if(IS_POST){
            $where = validate([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'title'       => [[],false,true,['like','title']],
                'state'       => [['in'=>[1,2]],false,true,['eq','state']],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ]);
            if(!is_array($where))
                showError(10006);

            if(!isset($where['state']))
                $where['state'] = ['neq',3];
            $pageNo = $where['page_no'];
            unset($where['page_no']);
            $pageSize = $where['page_size'] > 1000 ? 1000 : $where['page_size'];
            unset($where['page_size']);
            $model = M(self::T_BANNER);
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


    public function edit(){
        $model = M(self::T_BANNER);
        if(IS_POST){
            $id = I('post.id');
            if($id){
                $user = $model->where('id ='.$id)->find();
                if(!$user)
                    showError(20004);//不存在
                $rule = [
                    'title'  => [],
                    'url'    => [[],false,true],
                    'state'  => [['in'=>[1,2,3]]]
                ];
            }else{
                $rule = [
                    'title' => [[],true,false],
                    'url'   => [[],true,false],
                ];
            }
            $data = validate($rule);
            if(!is_array($data))
                showError(10006);//参数错误

            if(isset($_FILES['banner_upload'])){
                $upload = new Upload(); // 实例化上传类
                $upload->savePath = '/banner/';// 设置原图上传目录
                $upload->saveName = uniqid();
                $info = $upload->upload();
                if(!$info)
                    showError(20002,$upload->getError());
                foreach ($info as $v){
                    $data['img'] = '/Uploads'.$v['savepath'].$v['savename'];
                }
            }

            if(isset($data['title'])){
                $user = $model->where("title ='".$data['title']."'")->find();
                if($user && $user['id'] != $id)
                    showError(20000);//存在同名
            }

            if($id){
                if($model->where('id ='.$id)->save($data) === false)
                    showError(20002);//更新失败
            }else{
                $data['created_at'] = time();
                $insertId = $model->add($data);
                if(!$insertId)
                    showError(20001);//创建失败
            }

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

        $model = M(self::T_BANNER);
        $res = $model->where(['id'=>['in',$data['id']]])->setField('state',3);
        if($res === false)
            showError(20002);
        returnResult();
    }
}