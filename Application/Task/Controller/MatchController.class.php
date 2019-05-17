<?php
/**
 * Created by PhpStorm.
 * User: leasin
 * Date: 2019/5/17
 * Time: 16:33
 */

namespace Task\Controller;

use Common\Consts\Scheme;

class MatchController extends CommonController
{
    public function matching(){
        $log = 'order.match';
        writeLog('开始匹配用户',$log,'DEBUG');
        $model = M(Scheme::COMMISSION);
        $orders = $model->field('id,special_id')->limit(100)->where('state = 0')->select();
        if(!$orders){
            writeLog('暂无未匹配的订单',$log,'DEBUG');
            return true;
        }
        $memberId = array_unique(array_column($orders,'special_id'));
        $memberModel = M(Scheme::USER);
        $members = $memberModel->field('id,special_id,referee_id,channel_id')
            ->where(['special_id' => ['in',$memberId]])->select();
        if(!$members){
            writeLog('未找到会员',$log,'DEBUG');
            return true;
        }
        //查询二级推荐人
        $refereeId = array_unique(array_column($members,'referee_id'));
        $referees = $memberModel->field('id,referee_id')
            ->where(['id' => ['in',$refereeId]])->select();
        $referees = array_column($referees,'referee_id','id');

        $members = array_column($members,null,'special_id');
        //组装用户信息
        array_walk($orders,function(&$v)use($members,$referees){
            if(isset($members[$v['special_id']])){
                $v['user_id'] = $members[$v['special_id']]['id'];
                $v['referee_id'] = $members[$v['special_id']]['referee_id'];
                $v['channel_id'] = $members[$v['special_id']]['channel_id'];
                if(isset($referees[$v['referee_id']])){
                    $v['grand_id'] = $referees[$v['referee_id']];
                }else{
                    $v['grand_id'] = 0;
                }
                $v['state'] = 1;
            }
        });
        $res = saveAll($orders,Scheme::COMMISSION);
        if(!$res){
            writeLog('数据保存失败，原因：'.$res,$log,'ERROR');
            return false;
        }
        writeLog('匹配完成',$log,'DEBUG');
        return true;
    }
}