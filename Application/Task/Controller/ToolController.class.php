<?php

namespace Task\Controller;
use Common\Consts\Scheme;

class ToolController extends CommonController
{
    /**
     * 根据已支付订单生成已结算订单模拟数据
     * @return bool
     */
    public function mock_settle_order(){
        echo 'Mock Start'.PHP_EOL;
        $wantTotal = isset($_GET['total']) && is_numeric($_GET['total']) ? $_GET['total'] :100;
        echo 'You Want Mock '.$wantTotal.' data!'.PHP_EOL;
        $page = 1;
        $limit = 100;
        $total = 0;
        $commissionModel = M(Scheme::COMMISSION);
        $settleModel = M(Scheme::S_ORDER);
        $time = time();
        while(true){
            echo 'page:'.$page.PHP_EOL;
            $orders = $commissionModel
                ->field('trade_id,relation_id,special_id,pub_share_pre_fee')
                ->page($page,$limit)->select();
            if(!$orders){
                echo 'No orders!'.PHP_EOL;
                break;
            }
            array_walk($orders,function(&$v)use($time){
                $v['created_at'] = $v['create_time'] = $time;
                $v['total_commission_fee'] = $v['pub_share_pre_fee'];
                unset($v['pub_share_pre_fee']);
            });
            $res = $settleModel->addAll($orders);
            if(!$res){
                die('Add Error!'.PHP_EOL);
            }

            $total += count($orders);
            if($total > $wantTotal){
                break;
            }
            $page ++;
        }
        echo 'Mock Complete!Total:'.$total.PHP_EOL;
        return true;
    }

}