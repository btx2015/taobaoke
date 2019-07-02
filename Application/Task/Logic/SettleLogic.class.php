<?php

namespace Task\Logic;

use Common\Consts\Scheme;

class SettleLogic extends \Think\Model
{

    public $trueTableName = Scheme::SETTLE;

    /**
     * @param array $objects 分佣对象 键值表示该对象分佣排序
     * @param array $rateInfo 分佣比例
     * @param array $order 订单信息
     * @param string $field 分佣金额在订单中的字段名
     * @return array 返回二位数组 包含每个分佣对象的分佣金额 等级 分佣类型
     */
    public function cal($objects,$rateInfo,$order,$field = 'pub_share_pre_fee'){
        $amounts = [];
        foreach($objects as $key => $object){
            $memberRate = 0;
            //顺位累加分佣比例
            foreach($rateInfo as $level => $rate){
                $memberRate += $rate;
                unset($rateInfo[$level]);
                if($key == $level)
                    break;
            }
            if($object['id'] == $order['member_id']){
                if(isset($order['special_id'])){
                    $type = 1;//自购分佣
                }else{
                    $type = 2;//分享分佣
                }
            }else{
                if($object['level'] < 3){
                    $type = 3;//推荐分佣
                }else{
                    $type = 4;//运营商分佣
                }
            }
            $amount = round($order[$field] * $memberRate,2);
            $amounts[] = [
                'type' => $type,
                'member_id' => $object['id'],
                'member_level' => $object['level'],
                'amount' => $amount
            ];
        }
        return $amounts;
    }

    public function getObjects($memberInfo,$refereeInfo,$order = [],$levelUp = []){
        $createTime = strtotime($order['create_time']);
        //获取订单生成时用户级别
        if($levelUp)
            $memberInfo = $this->getLevel($memberInfo,$createTime,$levelUp);
        $objects[$memberInfo['level']] = $memberInfo;
        //查询推荐体系
        if($memberInfo['level'] < 4 && $memberInfo['referee_id']){
            $referee = $refereeInfo[$memberInfo['referee_id']];
            if($levelUp)
                $referee = $this->getLevel($referee,$createTime,$levelUp);
            if($referee['level'] == 4){//推荐人是超级运营商
                $objects[4] = $referee;
            }else{
                if($referee['level'] == 3){//推荐人是普通运营商
                    $objects[3] = $referee;
                    if($referee['referee_id']){
                        $parentReferee = $refereeInfo[$referee['referee_id']];
                        if($levelUp)
                            $parentReferee = $this->getLevel($parentReferee,$createTime,$levelUp);
                        //推荐人的推荐人是超级运营商
                        if($parentReferee['level'] == 4){
                            $objects[4] = $parentReferee;
                        }
                    }
                }else{
                    $objects[2] = $referee;
                    //查找运营商
                    foreach($refereeInfo as $v){
                        if($v['level'] > 2 ){
                            $parentReferee = $v;
                            if($levelUp)
                                $parentReferee = $this->getLevel($parentReferee,$createTime,$levelUp);
                            if($parentReferee['level'] < 3){
                                continue;
                            }
                            $objects[$parentReferee['level']] = $parentReferee;
                            //运营商是普通运营商
                            if($parentReferee['level'] != 4 && $parentReferee['referee_id']) {
                                $grandReferee = $refereeInfo[$parentReferee['referee_id']];
                                if($levelUp)
                                    $grandReferee = $this->getLevel($grandReferee, $createTime, $levelUp);
                                if ($grandReferee['level'] != 4) {
                                    continue;
                                }
                                //普通运营商的推荐人是超级运营商
                                $objects[4] = $grandReferee;
                            }
                            break;
                        }
                    }
                }
            }

        }
        ksort($objects);
        return $objects;
    }

    private function getLevel($memberInfo,$createTime,$levelUp){
        if(isset($levelUp[$memberInfo['id']])){
            $ups = $levelUp[$memberInfo['id']];
            //追溯订单创建时的用户的等级
            foreach($ups as $memberId => $up){
                //升级时间在订单创建之前
                if($up['up_time'] < $createTime){
                    $memberInfo['level'] = $up['old_level'];
                }
            }
        }
        return $memberInfo;
    }

    public function getPartner($refereeInfo){
        foreach($refereeInfo as $referee){
            if($referee['partner_id']){
                return $referee['partner_id'];
            }
        }
        return 0;
    }

}