<?php

namespace Task\Controller;

use Common\Consts\Scheme;

class OrderController extends CommonController
{

    const SYNC_PERIOD = 300;//订单同步时间间隔 5分钟

    /**
     * 接口文档
     * https://developer.alibaba.com/docs/api.htm?spm=a219a.7395905.0.0.29e675fex8tgK4&apiId=24527
     * @return bool
     */
    public function sync_pay_v1(){
        if(isset($_GET['start'])){
            $startTime = date('Y-m-d H:i:s',$_GET['start']);
        }else{//2019-05-06 13:22:00   1557120120
            $time = strtotime(date('Y-m-d H:i')) - self::SYNC_PERIOD;
            $startTime = date('Y-m-d H:i:s',$time);
        }
        $fields = <<<columns
tb_trade_parent_id,tb_trade_id,num_iid,item_title,item_num,price,pay_price,seller_nick,seller_shop_title,commission,
commission_rate,unid,create_time,earning_time,tk3rd_pub_id,tk3rd_site_id,tk3rd_adzone_id,relation_id,tb_trade_parent_id,
tb_trade_id,num_iid,item_title,item_num,price,pay_price,seller_nick,seller_shop_title,commission,commission_rate,unid,
create_time,earning_time,tk3rd_pub_id,tk3rd_site_id,tk3rd_adzone_id,special_id,click_time,earning_time
columns;
        $params = [
            'method' => 'taobao.tbk.order.get',
            'fields' => $fields,
            'start_time' => $startTime,
            'span' => self::SYNC_PERIOD,
            'page_size' => 100,
            'tk_status' => 1,
            'order_query_type' => 'create_time',
            'order_scene' => 3
        ];
        return $this->sync_order_v1($params);
    }

    private function sync_order_v1($params){
        $log = 'order.pay';
        writeLog('订单同步开始',$log,'DEBUG');
        $basic = S('basic_info');
        if(!isset($basic['tbk_app_key']) || !$basic['tbk_app_key']
            || !isset($basic['tbk_app_secret']) || !$basic['tbk_app_secret']){
            writeLog('淘宝客配置错误',$log,'ERROR');
            return false;
        }

        $pageNo = 1;
        $total = 0;
        Vendor('Taobaoke');
        $tbk = new \Taobaoke($basic['tbk_app_key'],$basic['tbk_app_secret']);
        while(true){
            echo 'page:'.$pageNo;
            $params['page_no'] = $pageNo;
            $res = $tbk->request($params);
            if(isset($res['result'])){
                $error = '淘宝客接口请求失败。请求参数：'.json_encode($params).'；返回结果：'.$res['msg'];
                writeLog($error,$log,'ERROR');
                echo 'request error';
                return false;
            }else{
                $data = $res['tbk_order_get_response']['results']['n_tbk_order'];
                if(empty($data)){
                    $info = '订单同步完成。同步订单数量：'.$total;
                    writeLog($info,$log,'DEBUG');
                    echo 'complete. total:'.$total;
                    return true;
                }else{
                    $insertId = $this->add_data($data);
                    if(!$insertId){
                        $error = '订单保存失败。请求参数：'.json_encode($params);
                        writeLog($error,$log,'ERROR');
                        echo 'save failed';
                        return false;
                    }
                    $total += count($data);
                    $pageNo ++;
                }
            }
        }
        return true;
    }

    /**
     * https://open.taobao.com/api.htm?docId=43328&docType=2
     * @return bool
     */
    public function sync_pay_v2(){
        if(isset($_GET['end'])){
            $time = $_GET['end'];
        }else{
            $time = strtotime(date('Y-m-d H:i'));
        }
        if(isset($_GET['start'])){
            $startTime = date('Y-m-d H:i:s',$_GET['start']);
        }else{
            $startTime = date('Y-m-d H:i:s',$time - self::SYNC_PERIOD);
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
        return $this->sync_order_v2($params);
    }

    private function sync_order_v2($params){
        $log = 'order.pay';
        writeLog('订单同步开始',$log,'DEBUG');
        $basic = S('basic_info');
        if(!isset($basic['tbk_app_key']) || !$basic['tbk_app_key']
            || !isset($basic['tbk_app_secret']) || !$basic['tbk_app_secret']){
            writeLog('淘宝客配置错误',$log,'ERROR');
            return false;
        }

        $pageNo = 1;
        $total = 0;
        Vendor('Taobaoke');
        $tbk = new \Taobaoke($basic['tbk_app_key'],$basic['tbk_app_secret']);
        while(true){
            $params['page_no'] = $pageNo;
            $res = $tbk->request($params);
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