<?php

namespace Admin\Controller\Member;

use Admin\Controller\CommonController;
use Common\Consts\Scheme;


class LevelController extends CommonController
{

    public function index(){
        if(IS_POST){
            $model = M(Scheme::U_LEVEL);
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'id'          => [['num'],false,true,['eq','b.id']],
                'username'    => [[],false,true,['like','b.username']],
                'name'        => [[],false,true,['like','b.name']],
                'phone'       => [['phone'],false,true,['like','b.phone']],
                'level'       => [['num'],false,true,['eq','a.level']],
                'state'       => [['in'=>[1,2]],false,true,['eq','a.state']],
                'create_from' => [['time'],false,true,['egt','a.created_at']],
                'create_to'   => [['time'],false,true,['elt','a.created_at']],
            ],'a');
            $list = $model->alias('a')
                ->join('left join '.Scheme::USER.' b on a.member_id = b.id')
                ->join('left join '.Scheme::SYS_ADMIN.' c on a.admin_id = c.id')
                ->field('a.*,b.username,b.name,b.phone,b.wx_nickname,c.usa')
                ->where($where)->page($pageNo,$pageSize)
                ->order('state asc,up_time desc')->select();
            $admin = [];
            if($list){
                $adminId = array_unique(array_column($list,'admin_id'));
                $admin = M(Scheme::SYS_ADMIN)->where(['id'=>['in',$adminId]])->select();
                $admin = array_column($admin,'usa','id');
            }
            returnResult([
                'list' => handleRecords([
                    'state'      => ['translate','up_state','state_str'],
                    'up_time'    => ['time','Y-m-d H:i:s','up_time_str'],
                    'created_at' => ['time','Y-m-d H:i:s','created_at_str'],
                    'old_level'  => ['translate','member_level','level_str'],
                    'admin_id'   => ['array_walk',$admin,'admin_id_str']
                ],$list),
                'total' =>$model->alias('a')->count()
            ]);
        }else{
            $translate = C('translate');
            $this->assign([
                'level' => $translate['member_level'],
                'state' => $translate['up_state'],
            ]);
            $this->display();
        }
    }

    public function edit(){
        $model = M(Scheme::U_LEVEL);
        if(IS_POST){
            $rule = [
                'id'    => [['num']],
                'state' => [['in'=>[0,2]]],
                'note'  => []
            ];
            $data = beforeSave($model,$rule,[]);
            $level = $model->where(['id'=>$data['id']])->find();
            if(!$level)
                showError(20004);//不存在
            $data['admin_id'] = $_SESSION['adminInfo']['id'];
            $data['up_time'] = time();
            if($model->save($data) === false)
                showError(20002);//更新失败
            returnResult();
        }else{
            $id = I('get.id');
            $level = $model->where('id ='.$id)->find();
            if(!$level)
                showError(20004);//不存在
            $member = M(Scheme::USER)->where(['id'=>$level['member_id']])->find();
            $translate = C('translate');
            $level['old_level'] = $translate['member_level'][$level['old_level']];
            $level['new_level'] = $translate['member_level'][$level['new_level']];
            $level['name'] = $member['name'];
            $level['phone'] = $member['phone'];
            returnResult($level);
        }
    }
}