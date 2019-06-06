<?php

namespace Admin\Controller\Member;

use Admin\Controller\CommonController;
use Common\Consts\Scheme;

class PartnerController extends CommonController
{

    public function index(){
        if(IS_POST){
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'username'    => [[],false,true,['like','b.username']],
                'name'        => [[],false,true,['like','b.name']],
                'phone'       => [['phone'],false,true,['like','b.phone']],
                'state'       => [['in'=>[1,2]],false,true,['eq','a.state']],
                'create_from' => [['time'],false,true,['egt','a.created_at']],
                'create_to'   => [['time'],false,true,['elt','a.created_at']],
            ],'a');

            $list = M(Scheme::U_PARTNER)->alias('a')
                ->join('left join '.Scheme::USER.' b on a.member_id = b.level')
                ->join('left join '.Scheme::U_LEVEL.' c on b.level = c.id')
                ->field('a.*,b.username,b.name,b.phone,c.name as level_str')
                ->where($where)->page($pageNo,$pageSize)->select();

            returnResult([
                'list' => handleRecords([
                    'state'      => ['translate','state','state_str'],
                    'created_at' => ['time','Y-m-d H:i:s','created_at_str'],
                    'rate'       => ['percent','','rate_str']
                ],$list),
                'total' =>M(Scheme::U_PARTNER)->alias('a')
                    ->join('left join '.Scheme::USER.' b on a.member_id = b.level')
                    ->where($where)->count()
            ]);
        }else{
            $this->display();
        }
    }

    public function add(){
        $model = M(Scheme::U_PARTNER);
        $rule = [
            'member_id' => [['num'],true],
        ];
        $data = validate($rule);
        if(!is_array($data))
            showError(10006);//参数错误
        $memberModel = M(Scheme::USER);
        $member = $memberModel->where([
            'id' => $data['member_id'],
            'state' => 1,
            'partner_id' => 0
        ])->find();
        if(!$member)
            showError(20004);
        $data['created_at'] = time();
        M()->startTrans();
        $insertId = $model->add($data);
        if(!$insertId){
            M()->rollback();
            showError(20001);//创建失败
        }
        $res = $memberModel->where(['id' => $data['member_id']])->save([
            'partner_id' => $insertId
        ]);
        if(!$res){
            M()->rollback();
            showError(20001);//创建失败
        }
        M()->commit();
        returnResult();
    }

    public function edit(){
        $model = M(Scheme::U_PARTNER);
        if(IS_POST){
            $rule = [
                'id'        => [['num'],true],
                'member_id' => [['num']],
                'rate'      => [['num','percent']],
            ];
            $data = beforeSave($model,$rule,['id']);
            if($model->save($data) === false)
                showError(20002);//更新失败
            returnResult();
        }else{
            $id = I('get.id');
            $user = $model->where('id ='.$id)->find();
            if(!$user)
                showError(20004);//不存在
            $user['rate'] = $user['rate']*100;
            returnResult($user);
        }
    }

    public function del(){
        $rule = ['id' => [[],true,false]];
        $data = validate($rule);
        if(!is_array($data))
            showError(10006);//参数错误

        $model = M(Scheme::U_PARTNER);
        $res = $model->where(['id'=>['in',$data['id']]])->setField('state',3);
        if($res === false)
            showError(20002);
        returnResult();
    }

}