<?php

namespace Admin\Controller\Member;


use Admin\Controller\CommonController;
use Common\Consts\Scheme;

class WithdrawController extends CommonController
{

    const T_WITHDRAW = 'tr_member_withdraw';

    const T_MEMBER = 'tr_member';

    const T_ADMIN = 'tr_sys_admin';

    public function index(){
        if(IS_POST){
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'username'    => [[],false,true,['like','b.username']],
                'phone'       => [['phone'],false,true,['like','b.phone']],
                'type'        => [[],false,true,['eq','a.type']],
                'state'       => [['in'=>[1,2]],false,true,['eq','a.state']],
                'create_from' => [['time'],false,true,['egt','a.created_at']],
                'create_to'   => [['time'],false,true,['elt','a.created_at']],
                'audit_from'  => [['time'],false,true,['egt','a.audit_time']],
                'audit_to'    => [['time'],false,true,['elt','a.audit_time']],
            ],false);

            $list = M(self::T_WITHDRAW)->alias('a')
                ->join('left join '.self::T_MEMBER.' b on a.user_id = b.id')
                ->join('left join '.self::T_ADMIN.' c on a.admin_id = c.id')
                ->field('a.*,b.username,b.phone,c.name as admin_id_str')
                ->where($where)->page($pageNo,$pageSize)
                ->order('state asc,created_at desc')->select();

            returnResult([
                'list' => handleRecords([
                    'state'           => ['translate','audit_state','state_str'],
                    'type'            => ['translate','withdraw_type','type_str'],
                    'created_at'      => ['time','Y-m-d H:i:s','created_at_str'],
                    'audit_time'      => ['time','Y-m-d H:i:s','audit_time_str'],
                ],$list),
                'total' =>M(self::T_WITHDRAW)->alias('a')
                    ->join('left join '.self::T_MEMBER.' b on a.user_id = b.id')
                    ->where($where)->count()
            ]);
        }else{
            $translate = C('TRANSLATE');
            $this->assign([
                'state' => $translate['audit_state'],
                'type'  => $translate['withdraw_type']
            ]);
            $this->display();
        }
    }

    public function audit(){
        $model = M(self::T_WITHDRAW);
        if(IS_POST){
            $update = validate([
                'id'    => [[],true,false],
                'state' => [['in'=>[2,3]],true,false],
                'descr' => []
            ]);
            if(!is_array($update))
                showError(10006);
            if($update['state'] == 3 && !$update['descr'])
                showError(10006,'请填写拒绝理由');

            $where = 'id='.$update['id'];

            M()->startTrans();
            $record = $model->where($where)->find();
            if(!$record || $record['state'] != 1)
                showError(20004);

            $update['admin_id'] = $_SESSION['adminInfo']['id'];
            $update['audit_time'] = time();

            $memberModel = M(Scheme::USER);

            $member = $memberModel->where(['id' => $record['user_id']])->find();
            if(!$member){
                M()->rollback();
                showError(20004,'用户不存在');
            }
            if($update['state'] == 3){
                $memberUpdate = [
                    'available_fund' => $member['available_fund'] + $record['amount'],
                    'frozen_withdraw' => $member['frozen_withdraw'] - $record['amount'],
                ];
            }else{
                $memberUpdate = [
                    'frozen_withdraw' => $member['frozen_withdraw'] - $record['amount'],
                ];
            }


            $res = $model->where($where)->save($update);

            if(!$res){
                M()->rollback();
                showError(20002,'审核失败');
            }

            $res = $memberModel->where(['id' => $record['user_id']])->save($memberUpdate);
            if(!$res){
                M()->rollback();
                showError(20002,'审核失败');
            }
            M()->commit();
            returnResult();
        }else{
            $id = I('get.id');
            if(!$id)
                showError(10006);
            $record = $model->alias('a')
                ->join('left join '.self::T_MEMBER.' b on a.user_id = b.id')
                ->field('a.id,a.state,a.type,a.account,a.type,a.amount,a.note,b.username,b.phone')
                ->where('a.id='.$id)->find();
            if(!$record || $record['state'] != 1)
                showError(20004);
            if($record['type'] == 1){
                $note = json_decode($record['note'],true);
                $record['location'] = '';
                foreach($note as $k => $v){
                    if($k === 'bank_name')
                        $record['bank_name'] = $v;
                    else
                        $record['location'] .= $v." ";
                }
            }
            $translate = C('TRANSLATE')['withdraw_type'];
            $record['type_str'] = $translate[$record['type']];
            returnResult($record);
        }
    }
}