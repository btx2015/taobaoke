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
        return $this->sync_order_v2($params,$log = 'order');
    }

    private function sync_order_v2($params,$log){

        $pageNo = 1;
        $total = 0;
        while(true){
            $params['page_no'] = $pageNo;
            $res = $this->tbk->request($params);
            if(isset($res['result'])){
                $error = '淘宝客接口请求失败。请求参数：'.json_encode($params).'；返回结果：'.$res['msg'];
                writeLog($error,$log,'ERROR');
                return false;
            }else{
                $data = $res['tbk_order_details_get_response']['data']['results']['publisher_order_dto'];
                if(empty($data)){
                    $info = '订单同步完成。同步订单数量：'.$total;
                    writeLog($info,$log,'DEBUG');
                    return false;
                }else{
                    $insertId = $this->add_data($data);
                    if(!$insertId){
                        $error = '订单保存失败。请求参数：'.json_encode($params);
                        writeLog($error,$log,'ERROR');
                        return false;
                    }
                    $total += count($data);
                    $params['position_index'] = $res['tbk_order_details_get_response']['data']['position_index'];
                    $pageNo ++;
                }
            }
        }
        return true;
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