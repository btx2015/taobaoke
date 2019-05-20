<?php

namespace Task\Controller;

use Common\Consts\Scheme;

class MatchController extends CommonController
{

    const LIMIT = 100;//每次匹配数量

    public function matching(){
        $log = 'match';
        if(isset($_GET['type'])){
            $log .= '.'.$_GET['type'];
        }
        writeLog('开始匹配用户',$log,'DEBUG');
        echo 'Matching start'.PHP_EOL;
        $model = M(Scheme::COMMISSION);
        $orderMatch = $orderTotal = 0;//未匹配订单数量 = 匹配成功订单数量 = 0
        $run = true;
        $page = 1;
        while($run){
            $orders = $model->field('id,special_id')
                ->limit(self::LIMIT)->page($page)->where('state = 0')->select();
            if(!$orders){
                writeLog('暂无未匹配的订单',$log,'DEBUG');
                echo 'No Matching Orders'.PHP_EOL;
                break;
            }
            $orderMatch += count($orders);
            $memberId = array_unique(array_column($orders,'special_id'));
            $memberModel = M(Scheme::USER);
            $members = $memberModel->field('id,special_id,referee_id,channel_id')
                ->where(['special_id' => ['in',$memberId]])->select();
            if(!$members){
                writeLog('以下special_id未找到会员。'.json_encode($memberId),$log,'ERROR');
                continue;
            }
            //查询二级推荐人
            $refereeId = array_unique(array_column($members,'referee_id'));
            $referees = $memberModel->field('id,referee_id')
                ->where(['id' => ['in',$refereeId]])->select();
            $referees = array_column($referees,'referee_id','id');

            $members = array_column($members,null,'special_id');
            $total = 0;
            //组装用户信息
            array_walk($orders,function(&$v)use($members,$referees,&$total){
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