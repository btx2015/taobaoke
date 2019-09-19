<?php
/**
 * 接口文档
 * https://open.taobao.com/api.htm?docId=43328&docType=2
 */
namespace Task\Controller;

use Common\Consts\Scheme;

class OrdersController extends CommonController
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
    public function sync_pay_v2(){
        $log = 'order.pay';
        writeLog('订单同步开始',$log,'DEBUG');
        echo 'sync start'.PHP_EOL;
        if(isset($_GET['end'])){
            $time = $_GET['end'];
        }else{
            $time = strtotime(date('Y-m-d H:i'));
        }
        if(isset($_GET['start'])){
            $startTime = date('Y-m-d H:i:s',$_GET['start']);
        }else{
            $startTime = date('Y-m-d H:i:s',$time - self::PAY_PERIOD);
        }
        $endTime = date('Y-m-d H:i:s',$time);
        $params = [
            'method' => 'taobao.tbk.order.details.get',
            'query_type' => 1,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'page_size' => 100,
            'tk_status' => 12,
            'order_scene' => 3
        ];
        $total = $this->sync_order_v2($params,Scheme::COMMISSION,$log);
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
    public function sync_settle_v2(){
        $time = time();
        S('sync_settle_lock',$time);
        if(isset($_GET['start'])){
            $startTime = $_GET['start'];
        }else{
            $startTime = M(Scheme::SETTLE)->where(['state'=>['egt',2]])->order('created_at desc')->getField('created_at');
        }
        $endTime = isset($_GET['end']) ? $_GET['end'] : $time;
        $params = [
            'method' => 'taobao.tbk.order.details.get',
            'query_type' => 1,
            'page_size' => 100,
            'tk_status' => 12,
            'order_scene' => 3
        ];
        $log = 'order.settle';
        writeLog('订单同步开始',$log,'DEBUG');
        echo 'sync start'.PHP_EOL;
        $total = 0;
        while($startTime < $endTime){
            $params['start_time'] = date('Y-m-d H:i:s',$startTime);
            $params['end_time'] = date('Y-m-d H:i:s',$startTime + self::SETTLE_PERIOD);
            writeLog('开始同步'.$params['start_time'].'的订单',$log,'DEBUG');
            echo $params['start_time'].PHP_EOL;
            $res = $this->sync_order_v2($params,Scheme::S_ORDER,$log);
            if($res === false){
                break;
            }else{
                $startTime += self::SETTLE_PERIOD;
                $total += $res;
            }
        }

        $info = '订单同步完成。同步订单数量：'.$total;
        S('sync_settle_lock',null);
        writeLog($info,$log,'DEBUG');
        echo 'complete. total:'.$total.PHP_EOL;
        return true;
    }

    private function sync_order_v2($params,$scheme,$log){
        $pageNo = 1;
        $total = 0;
        while(true){
            $params['page_no'] = $pageNo;
            $res = $this->tbk->request_v2($params);
            if(isset($res['result'])){
                $error = '淘宝客接口请求失败。请求参数：'.json_encode($params).'；返回结果：'.$res['msg'];
                writeLog($error,$log,'ERROR');
                return false;
            }else{
                $data = $res['tbk_order_details_get_response']['data']['results'];
                if(empty($data) || !isset($data['publisher_order_dto']) || empty($data['publisher_order_dto'])){
                    break;
                }else{
                    $insertId = $this->add_data($data['publisher_order_dto'],$scheme);
                    if(!$insertId){
                        $error = '订单保存失败。请求参数：'.json_encode($params);
                        writeLog($error,$log,'ERROR');
                        return false;
                    }
                    $total += count($data['publisher_order_dto']);
                    $params['position_index'] = $res['tbk_order_details_get_response']['data']['position_index'];
                    $pageNo ++;
                }
            }
        }
        return $total;
    }

    private function add_data($data,$scheme){
        $model = M($scheme);
        $time = time();
        array_walk($data,function(&$v) use($time){
            $v['num_iid'] = $v['item_id'];
            $v['create_time'] = $v['tk_create_time'];
            $v['created_at'] = $time;
        });
        $insertId = $model->addAll($data);
        return $insertId;
    }
}