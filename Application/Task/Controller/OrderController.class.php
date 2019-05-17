<?php
/**
 * 接口文档
 * https://developer.alibaba.com/docs/api.htm?spm=a219a.7395905.0.0.29e675fex8tgK4&apiId=24527
 */

namespace Task\Controller;

use Common\Consts\Scheme;

class OrderController extends CommonController
{

    const PAY_PERIOD = 300;//已支付订单同步时间范围 5分钟

    const SETTLE_PERIOD = 1200;//已支付订单同步时间范围 20分钟

    private $tbk = '';

    public function _initialize(){
        parent::_initialize();
        $basic = S('basic_info');
        if(!isset($basic['tbk_app_key']) || !$basic['tbk_app_key']
            || !isset($basic['tbk_app_secret']) || !$basic['tbk_app_secret']){
            writeLog('淘宝客配置错误','order.init','ERROR');
            exit('Config error');
        }
        Vendor('Taobaoke');
        $this->tbk = new \Taobaoke($basic['tbk_app_key'],$basic['tbk_app_secret']);
    }

    /**
     * 同步已支付订单
     * @return bool
     */
    public function sync_pay(){
        $log = 'order.pay';
        writeLog('订单同步开始',$log,'DEBUG');

        if(isset($_GET['start'])){
            $startTime = date('Y-m-d H:i:s',$_GET['start']);
        }else{//2019-05-06 13:22:00   1557120120
            $time = strtotime(date('Y-m-d H:i'));
            $startTime = date('Y-m-d H:i:s',$time) - self::PAY_PERIOD;
        }
        $fields = <<<columns
trade_parent_id,trade_id,num_iid,item_title,item_num,price,pay_price,seller_nick,seller_shop_title,commission,
commission_rate,create_time,earning_time,tk_status,tk3rd_type,tk3rd_pub_id,order_type,income_rate,pub_share_pre_fee,
subsidy_rate,subsidy_type,terminal_type,auction_category,site_id,site_name,adzone_id,adzone_name,alipay_total_price,
total_commission_rate,total_commission_fee,subsidy_fee,relation_id,special_id,click_time
columns;
        $params = [
            'method' => 'taobao.tbk.order.get',
            'fields' => $fields,
            'start_time' => $startTime,
            'span' => self::PAY_PERIOD,
            'page_size' => 100,
            'tk_status' => 1,
            'order_query_type' => 'create_time',
            'order_scene' => 3
        ];
        $total = $this->sync_order($params,$log);
        if($total === false)
            return false;
        $info = '订单同步完成。同步订单数量：'.$total;
        writeLog($info,$log,'DEBUG');
        echo 'complete. total:'.$total.PHP_EOL;
        return true;
    }

    /**
     * 同步已结算订单
     * @return bool
     */
    public function sync_settle(){
        $startTime = isset($_GET['start']) ? $_GET['start'] : strtotime(date('Y-m-01', strtotime('-1 month')));
        $endTime = isset($_GET['end']) ? $_GET['end'] : strtotime(date('Y-m-01'));
        $fields = <<<columns
tb_trade_id,total_commission_fee,total_commission_rate,earning_time,relation_id,special_id
columns;
        $params = [
            'method' => 'taobao.tbk.order.get',
            'fields' => $fields,
            'span' => self::SETTLE_PERIOD,
            'page_size' => 100,
            'tk_status' => 12,
            'order_query_type' => 'settle_time',
            'order_scene' => 3
        ];
        $log = 'order.settle';
        writeLog('订单同步开始',$log,'DEBUG');
        echo 'sync start'.PHP_EOL;
        $total = 0;
        while($startTime <= $endTime){
            $params['start_time'] = date('Y-m-d H:i:s',$startTime);
            writeLog('开始同步'.$params['start_time'].'的订单',$log,'DEBUG');
            echo $params['start_time'].PHP_EOL;
            $res = $this->sync_order($params,$log);
            if($res === false){
                break;
            }else{
                $startTime += self::SETTLE_PERIOD;
                $total += $res;
            }
        }

        $info = '订单同步完成。同步订单数量：'.$total;
        writeLog($info,$log,'DEBUG');
        echo 'complete. total:'.$total.PHP_EOL;
        return true;
    }

    private function sync_order($params,$log = 'order'){
        $pageNo = 1;
        $total = 0;
        $run = true;
        writeLog('接口请求开始，参数：'.json_encode($params),$log,'DEBUG');
        while($run){
            echo 'page:'.$pageNo.PHP_EOL;
            $params['page_no'] = $pageNo;
            writeLog('请求第'.$pageNo.'页。',$log,'DEBUG');
            $res = $this->tbk->request($params);
            if(isset($res['result'])){
                $error = '淘宝客接口请求失败。请求参数：'.json_encode($params).'；返回结果：'.$res['msg'];
                writeLog($error,$log,'ERROR');
                echo 'request error'.PHP_EOL;
                $run = false;
                break;
            }else{
                $data = $res['tbk_order_get_response']['results']['n_tbk_order'];
                if(empty($data)){
                    break;
                }else{
                    $insertId = $this->add_data($data);
                    if(!$insertId){
                        $error = '订单保存失败。请求参数：'.json_encode($params);
                        writeLog($error,$log,'ERROR');
                        echo 'save failed'.PHP_EOL;
                        $run = false;
                        break;
                    }
                    $current = count($data);
                    $total += $current;
                    $pageNo ++;
                }
            }
            writeLog('第'.$pageNo.'页同步订单数量：'.$current,'DEBUG');
        }
        return $run ? $total : false;
    }

    private function add_data($data){
        $model = M(Scheme::COMMISSION);
        $time = time();
        array_walk($data,function(&$v) use($time){
            $v['created_at'] = $time;
        });
        $insertId = $model->addAll($data);
        return $insertId;
    }
}