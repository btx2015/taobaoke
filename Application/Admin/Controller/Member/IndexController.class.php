<?php
/**
 * Created by PhpStorm.
 * User: leasin
 * Date: 2019/1/14
 * Time: 14:39
 */

namespace Admin\Controller\Member;

use Admin\Controller\CommonController;

class IndexController extends CommonController
{
    const T_MEMBER = 'tr_member_info';

    const T_FLOW = 'tr_member_fund_flow';

    public function index(){
        if(IS_POST){
            $where = validate([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'id'          => [['num'],false,true,['eq','a.id']],
                'username'    => [[],false,true,['like','a.username']],
                'name'        => [[],false,true,['like','a.name']],
                'phone'       => [['phone'],false,true,['like','a.phone']],
                'state'       => [['in'=>[1,2]],false,true,['eq','a.state']],
                'login_ip'    => [[],false,true,['like','a.login_ip']],
                'create_from' => [['time'],false,true,['egt','a.created_at']],
                'create_to'   => [['time'],false,true,['elt','a.created_at']],
                'login_from'  => [['time'],false,true,['egt','a.login_time']],
                'login_to'    => [['time'],false,true,['elt','a.login_time']],
            ]);
            if(!is_array($where))
                showError(10006);

            if(!isset($where['a.state']))
                $where['a.state'] = ['neq',3];
            $pageNo = $where['page'];
            unset($where['page']);
            $pageSize = $where['rows'] > 1000 ? 1000 : $where['rows'];
            unset($where['rows']);

            $list = M(self::T_MEMBER)->alias('a')
                ->join('left join '.self::T_MEMBER.' b on a.referee_id = b.id')
                ->field('a.*,b.phone as referee_id_str')
                ->where($where)->page($pageNo,$pageSize)->select();

            returnResult([
                'list' => handleRecords([
                    'state'           => ['translate','state','state_str'],
                    'created_at'      => ['time','Y-m-d H:i:s','created_at_str'],
                    'login_time'      => ['time','Y-m-d H:i:s','login_time_str'],
                    'last_login_time' => ['time','Y-m-d H:i:s','last_login_time_str'],
                ],$list),
                'total' =>M(self::T_MEMBER)->alias('a')->where($where)->count()
            ]);
        }else{
            $this->display();
        }
    }

    public function edit(){
        $model = M(self::T_MEMBER);
        if(IS_POST){
            $id = I('post.id');
            if($id){
                $user = $model->where('id ='.$id)->find();
                if(!$user)
                    showError(20004);//不存在
                $rule = [
                    'username' => [],
                    'phone'    => [],
                    'password' => [['password']],
                    'state'    => [['in'=>[1,2]]]
                ];
            }else{
                $rule = [
                    'username' => [[],true],
                    'phone'    => [[],true,false],
                    'password' => [['password'],true,true],
                    'referee'  => []
                ];
            }
            $data = validate($rule);
            if(!is_array($data))
                showError(10006);//参数错误

            if(isset($data['phone'])){
                $user = $model->where("phone ='".$data['phone']."'")->find();
                if($user && $user['id'] != $id)
                    showError(20000);//存在相同
            }

            if($id){
                if(isset($data['referee'])){
                    $refer = $model->where(['phone' => $data['phone']])->find();
                    if(!$refer)
                        showError(20004,'推荐人不存在');
                    unset($data['referee']);
                    $data['referee_id'] = $refer['id'];
                }

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

        $model = M(self::T_MEMBER);
        $res = $model->where(['id'=>['in',$data['id']]])->setField('state',3);
        if($res === false)
            showError(20002);
        returnResult();
    }

    public function flow(){
        if(IS_POST){
            $where = validate([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'id'          => [['num'],false,true],
                'user_id'     => [[],false,true],
                'type'        => [[],false,true],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ]);
            if(!is_array($where))
                showError(10006);

            $pageNo = $where['page'];
            unset($where['page']);
            $pageSize = $where['rows'] > 1000 ? 1000 : $where['rows'];
            unset($where['rows']);

            $list = M(self::T_FLOW)->where($where)->page($pageNo,$pageSize)->select();

            returnResult([
                'list' => handleRecords([
                    'type'       => ['translate','flow_type','type_str'],
                    'created_at' => ['time','Y-m-d H:i:s','created_at_str'],
                ],$list),
                'total' =>M(self::T_FLOW)->where($where)->count()
            ]);
        }else{
            $id = I('get.id');
            $this->assign('userId',$id);
            $this->display();
        }
    }
}