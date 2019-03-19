<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/19 0019
 * Time: 23:40
 */

namespace Admin\Controller\Member;


use Admin\Controller\CommonController;

class WithdrawController extends CommonController
{

    const T_WITHDRAW = 'tr_member_withdraw';

    const T_MEMBER = 'tr_member_info';

    const T_ADMIN = 'tr_sys_admin';

    public function index(){
        if(IS_POST){
            $where = validate([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'username'    => [[],false,true,['like','b.username']],
                'phone'       => [['phone'],false,true,['like','b.phone']],
                'state'       => [['in'=>[1,2]],false,true,['eq','a.state']],
                'create_from' => [['time'],false,true,['egt','a.created_at']],
                'create_to'   => [['time'],false,true,['elt','a.created_at']],
                'audit_from'  => [['time'],false,true,['egt','a.audit_time']],
                'audit_to'    => [['time'],false,true,['elt','a.audit_time']],
            ]);
            if(!is_array($where))
                showError(10006);

            $pageNo = $where['page'];
            unset($where['page']);
            $pageSize = $where['rows'] > 1000 ? 1000 : $where['rows'];
            unset($where['rows']);

            $list = M(self::T_WITHDRAW)->alias('a')
                ->join('left join '.self::T_MEMBER.' b on a.user_id = b.id')
                ->join('left join '.self::T_ADMIN.' c on a.admin_id = c.id')
                ->field('a.*,b.id,b.username,b.phone,c.name as admin_id_str')
                ->where($where)->page($pageNo,$pageSize)
                ->order('state asc,created_at desc')->select();

            returnResult([
                'list' => handleRecords([
                    'state'           => ['translate','audit_state','state_str'],
                    'created_at'      => ['time','Y-m-d H:i:s','created_at_str'],
                    'audit_time'      => ['time','Y-m-d H:i:s','audit_time_str'],
                ],$list),
                'total' =>M(self::T_WITHDRAW)->alias('a')->where($where)->count()
            ]);
        }else{
            $state = C('TRANSLATE')['audit_state'];
            $this->assign('state',$state);
            $this->display();
        }
    }

    public function edit(){

    }
}