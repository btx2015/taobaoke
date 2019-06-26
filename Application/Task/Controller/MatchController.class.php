<?php

namespace Task\Controller;

use Common\Consts\Scheme;

class MatchController extends CommonController
{

    const LIMIT = 100;//每次匹配数量

    public function match_settle(){
        $log = 'match.settle';
        writeLog('开始匹配用户',$log,'DEBUG');
        echo 'Matching start'.PHP_EOL;
        $model = M(Scheme::S_ORDER);
        $orderMatch = $orderTotal = 0;//未匹配订单数量 = 匹配成功订单数量 = 0
        $run = true;
        $page = 1;
        while($run){
            $orders = $model->field('id,special_id,relation_id')
                ->limit(self::LIMIT)->page($page)->where('state = 0')->select();
            if(!$orders){
                writeLog('暂无未匹配的订单',$log,'DEBUG');
                echo 'No Matching Orders'.PHP_EOL;
                break;
            }
            $orderMatch += count($orders);
            $specialId = array_unique(array_column($orders,'special_id'));
            $memberModel = M(Scheme::USER);
            $memberSpecials = $memberModel->field('id,special_id')
                ->where(['special_id' => ['in',$specialId]])->select();
            if(!$memberSpecials){
                writeLog('以下special_id未找到会员。'.json_encode($specialId),$log,'ERROR');
                continue;
            }
            $memberSpecials = array_column($memberSpecials,'id','special_id');
            $relationId = array_unique(array_column($orders,'relation_id'));
            $memberRelations = $memberModel->field('id,relation_id')
                ->where(['relation_id' => ['in',$relationId]])->select();
            if(!$memberRelations){
                writeLog('以下special_id未找到会员。'.json_encode($relationId),$log,'ERROR');
                continue;
            }
            $memberRelations = array_column($memberRelations,'id','relation_id');

            array_walk($orders,function(&$v)use($memberSpecials,$memberRelations,&$total){
                if(isset($v['special_id']) && isset($memberSpecials[$v['special_id']])){
                    $v['member_id'] = $memberSpecials[$v['special_id']];
                }else if(isset($v['relation_id']) && isset($memberRelations[$v['relation_id']])){
                    $v['member_id'] = $memberSpecials[$v['relation_id']];
                }
                if($v['member_id']){
                    $total ++;
                }
            });

            $res = saveAll($orders,Scheme::S_ORDER);
            if(!$res){
                writeLog('数据保存失败，原因：'.$res,$log,'ERROR');
                echo 'Data Saved Failed'.PHP_EOL;
                $run = false;
                break;
            }
            $orderTotal += $total;
            $page ++;
        }
        if($run){
            writeLog('匹配完成。未匹配订单：'.$orderMatch.'；匹配成功数量：'.$orderTotal,$log,'DEBUG');
            echo 'Matching Completed.Total:'.$orderMatch.';Success:'.$orderTotal.PHP_EOL;
        }
        return $run;
    }

    public function match_pay(){
        $log = 'match.pay';
        writeLog('开始匹配用户',$log,'DEBUG');
        echo 'Matching start'.PHP_EOL;
        $model = M(Scheme::COMMISSION);
        $orderMatch = $orderTotal = 0;//未匹配订单数量 = 匹配成功订单数量 = 0
        $run = true;
        $page = 1;
        while($run){
            $memberSpecials = $memberRelations = [];
            $orders = $model->field('id,special_id')
                ->limit(self::LIMIT)->page($page)->where('state = 0')->select();
            if(!$orders){
                writeLog('暂无未匹配的订单',$log,'DEBUG');
                echo 'No Matching Orders'.PHP_EOL;
                break;
            }
            $memberModel = M(Scheme::USER);
            $orderMatch += count($orders);
            $specialId = array_unique(array_column($orders,'special_id'));
            if($specialId){
                $memberSpecials = $memberModel->field('id,special_id')
                    ->where(['special_id' => ['in',$specialId]])->select();
                if(!$memberSpecials){
                    writeLog('以下special_id未找到会员。'.json_encode($specialId),$log,'ERROR');
                    continue;
                }
                $memberSpecials = array_column($memberSpecials,'id','special_id');
            }

            $relationId = array_unique(array_column($orders,'relation_id'));
            if($relationId){
                $memberRelations = $memberModel->field('id,relation_id')
                    ->where(['relation_id' => ['in',$relationId]])->select();
                if(!$memberRelations){
                    writeLog('以下relation_id未找到会员。'.json_encode($relationId),$log,'ERROR');
                    continue;
                }
                $memberRelations = array_column($memberRelations,'id','relation_id');
            }

            array_walk($orders,function(&$v)use($memberSpecials,$memberRelations,&$total){
                if(isset($v['special_id']) && isset($memberSpecials[$v['special_id']])){
                    $v['member_id'] = $memberSpecials[$v['special_id']];
                }else if(isset($v['relation_id']) && isset($memberRelations[$v['relation_id']])){
                    $v['member_id'] = $memberSpecials[$v['relation_id']];
                }
                if($v['member_id']){
                    $v['state'] = 1;
                    $total ++;
                }
            });
            $res = saveAll($orders,Scheme::COMMISSION);
            if(!$res){
                writeLog('数据保存失败，原因：'.$res,$log,'ERROR');
                echo 'Data Saved Failed'.PHP_EOL;
                $run = false;
                break;
            }
            $orderTotal += $total;
            $page ++;
        }
        if($run){
            writeLog('匹配完成。未匹配订单：'.$orderMatch.'；匹配成功数量：'.$orderTotal,$log,'DEBUG');
            echo 'Matching Completed.Total:'.$orderMatch.';Success:'.$orderTotal.PHP_EOL;
        }
        return $run;
    }
}