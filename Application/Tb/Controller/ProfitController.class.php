<?php
namespace Tb\Controller;
use Common\Consts\Scheme;
class ProfitController extends CommonController {

    const T_VIDEO_BANNER = 'tr_video_banner';

    public function banner(){
    	$model = M(self::T_VIDEO_BANNER);
    	$banner = $model->where(['state'=>'1'])->order('sort')->select();
        ajaxReturn($banner);
    }

    public function indexggbanner(){
    	$model = M(self::T_MANAGE_BANNER);
    	$banner = $model->where(['state'=>'1'])->order('sort')->select();
        ajaxReturn($banner);
    }

    public function profit(){
        $currentProfit = $lastProfit = 0;
        $todayProfit = $yesterdayProfit = 0;
        $todayCount = $yesterdayCount = 0;
        $todayOther = $yesterdayOther = 0;
        $currentStart = strtotime(date('Y-m-01'));
        $todayStart = strtotime(date('Y-m-d'));
        $yesterdayStart = strtotime(date('Y-m-d',strtotime('-1 day')));
        $lastStart = strtotime(date('Y-m-01',strtotime('-1 month')));
        $end = time();
        $orderModel = M(Scheme::COMMISSION);

        //计算线上收益
        $type = ['user','referee','grand'];
        foreach($type as $v){
            $orders = $orderModel->where([
                $v.'_id' => $this->member['id'],
                'created_at' => ['between',[$lastStart,$end]]
            ])->field($v.'_profit,create_time')->select;
            foreach($orders as $order){
                $time = strtotime($order['create_time']);
                if($time >= $lastStart && $time < $currentStart){
                    $lastProfit += $order[$v.'_profit'];
                }
                if($time >= $currentStart && $time < $end){
                    $currentProfit += $order[$v.'_profit'];
                }
                if($time >= $yesterdayStart && $time < $todayStart){
                    $todayProfit += $order[$v.'_profit'];
                    $todayCount ++;
                }
                if($time >= $todayStart && $time < $end){
                    $yesterdayProfit += $order[$v.'_profit'];
                    $yesterdayCount ++;
                }
            }
        }
        //计算其他收益
        //@TODO
        ajaxReturn([
            'available_fund'    => $this->member['available_fund'],//账户余额
            'total_income'      => $this->member['total_income'],//累计收入
            'last_settle'       => $this->member['last_settle'],//上月结算收入
            'current_profit'    => $currentProfit,//本月预估收益
            'last_profit'       => $lastProfit,//上月预估收益
            'today_pay'         => $todayCount,//今日付款笔数
            'today_profit'      => $todayProfit,//今日预估收益
            'today_other'       => $todayOther,//今日其他
            'yesterday_pay'     => $yesterdayCount,//昨日付款笔数
            'yesterday_profit'  => $yesterdayProfit,//昨日预估收益
            'yesterday_other'   => $yesterdayOther,//昨日其他
        ]);
    }

    public function details(){
        $limit = I('post.limit');
        if(!$limit)
            $limit = 10;
        else
            $limit = $limit > 50 ? 10 : $limit;
        $page = I('post.page');
        if(!$page)
            $page = 1;
        $detailModel = M(Scheme::S_DETAIL);
        $details = $detailModel
            ->where([
                'user_id' => $this->member['id'],
                'state' => 3
            ])
            ->limit($limit)->page($page)->select();
        $data = [];
        if($details){
            $orderId = array_column($details,'order_id');
            $details = array_column($details,null,'order_id');
            $orderModel = M(Scheme::S_ORDER);
            $orders = $orderModel->where([
                'id' => $orderId
            ])->select();
            foreach($orders as $order){

                $month = date('Y',$order['earning_time']);
                $data[] = $order;
            }
        }
    }
}