<?php

namespace Admin\Controller\Member;

use Admin\Controller\CommonController;
use Common\Consts\Scheme;

class IndexController extends CommonController
{
    const T_MEMBER = 'tr_member';

    const T_FLOW = 'tr_member_fund_flow';

    const T_CHANNEL = 'tr_channel';

    public function index(){
        if(IS_POST){
            $model = M(Scheme::USER);
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'id'          => [['num'],false,true,['eq','a.id']],
                'username'    => [[],false,true,['like','a.username']],
                'name'        => [[],false,true,['like','a.name']],
                'phone'       => [['phone'],false,true,['like','a.phone']],
                'level'       => [['num'],false,true,['eq','level']],
                'state'       => [['in'=>[1,2]],false,true,['eq','a.state']],
                'login_ip'    => [[],false,true,['like','a.login_ip']],
                'create_from' => [['time'],false,true,['egt','a.created_at']],
                'create_to'   => [['time'],false,true,['elt','a.created_at']],
                'login_from'  => [['time'],false,true,['egt','a.login_time']],
                'login_to'    => [['time'],false,true,['elt','a.login_time']],
            ],'a');

            $list = $model->alias('a')
                ->join('left join '.Scheme::USER.' b on a.referee_id = b.id')
                ->field('a.*,b.username as referee_id_str')
                ->where($where)->page($pageNo,$pageSize)->select();

            returnResult([
                'list' => handleRecords([
                    'level'           => ['translate','member_level','level_str'],
                    'state'           => ['translate','state','state_str'],
                    'created_at'      => ['time','Y-m-d H:i:s','created_at_str'],
                    'login_time'      => ['time','Y-m-d H:i:s','login_time_str'],
                    'last_login_time' => ['time','Y-m-d H:i:s','last_login_time_str'],
                    'partner_id'      => ['isset',['是','否'],'partner_id_str'],
                ],$list),
                'total' => $model->alias('a')->where($where)->count()
            ]);
        }else{
            $level = C('translate')['member_level'];
            $this->assign('level',$level);
            $this->display();
        }
    }

    public function add(){
        $model = M(self::T_MEMBER);
        $rule = [
            'username'   => [[],true],
            'phone'      => [['phone'],true,false],
            'password'   => [['password'],true,true],
            'referee'    => [['phone']],
            'channel_id' => [['num'],1]
        ];
        $data = beforeSave($model,$rule,['phone']);
        if(isset($data['referee'])){
            $referee = $model->where(['phone'=>$data['referee']])->find();
            if(!$referee)
                showError(20004,'推荐人不存在');
            unset($data['referee']);
            $data['referee_id'] = $referee['id'];
        }

        $data['created_at'] = time();
        $insertId = $model->add($data);
        if(!$insertId)
            showError(20001);//创建失败
        returnResult();
    }

    public function edit(){
        $model = M(self::T_MEMBER);
        if(IS_POST){
            $rule = [
                'id'         => [['num']],
                'username'   => [],
                'phone'      => [],
                'password'   => [['password']],
                'state'      => [['in'=>[1,2]]],
                'channel_id' => [['num']],
                'referee'    => [['phone']]
            ];
            $data = beforeSave($model,$rule,['phone']);
            $user = $model->where(['id'=>$data['id']])->find();
            if(!$user)
                showError(20004);//不存在
            if(isset($data['referee'])){
                $refer = $model->where(['phone' => $data['referee']])->find();
                if(!$refer)
                    showError(20004,'推荐人不存在');
                unset($data['referee']);
                $data['referee_id'] = $refer['id'];
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