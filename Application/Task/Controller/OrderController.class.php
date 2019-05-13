<?php
/**
 * Created by PhpStorm.
 * User: leasin
 * Date: 2019/5/10
 * Time: 20:46
 */

namespace Task\Controller;
use Think\log;

class OrderController extends CommonController
{

    const T_ORDER = 'tr_commission_order';

    const SYNC_PERIOD = 300;

    public function order_sync(){
        writeLog('订单同步开始','Task','order','DEBUG');
        $basic = S('basic_info');
        if(!isset($basic['tbk_app_key']) || $basic['tbk_app_key']
            || !isset($basic['tbk_app_secret']) || $basic['tbk_app_secret']){
            writeLog('淘宝客配置错误','Task','order','ERROR');
            return false;
        }

        $startTime = strtotime(date('Y-m-d H:i')) - self::SYNC_PERIOD;
        $startTime = date('Y-m-d H:i:s',$startTime);
        $pageNo = 1;
        $total = 0;
        $tbk = new \Taobaoke($basic['tbk_app_key'],$basic['tbk_app_secret']);
        while(true){
            $params = [
                'method' => 'taobao.tbk.order.get',
                'fields' => 'trade_id,total_commission_fee,special_id,adzone_id,relation_id',
                'start_time' => $startTime,
                'span' => self::SYNC_PERIOD,
                'page_no' => $pageNo,
                'page_size' => 100,
                'tk_status' => 3,
                'order_query_type' => 'settle_time',
                'order_scene' => 3
            ];
            $res = $tbk->request($params);
            if($res['result'] === 'Y'){
                if(empty($data)){
                    $info = '订单同步完成。同步订单数量：'.$total;
                    writeLog($info,'Task','order','DEBUG');
                    return false;
                }else{
                    $insertId = $this->add_data($res['data']);
                    if(!$insertId){
                        $error = '订单保存失败。请求参数：'.json_encode($params);
                        writeLog($error,'Task','order','ERROR');
                        return false;
                    }
                    $total += count($res['data']);
                    $pageNo ++;
                }
            }else{
                $error = '淘宝客接口请求失败。请求参数：'.json_encode($params).'；返回结果：'.$res['msg'];
                writeLog($error,'Task','order','ERROR');
                return false;
            }
        }
        return true;
    }

    private function add_data($data){
        $model = M(self::T_ORDER);
        $order = [];
        foreach($data as $v){
            $order[] = [
                'order_sn'       => $v['trade_id'],
                'relation_id'    => $v['relation_id'],
                'special_id'     => $v['special_id'],
                'adzone_id'      => $v['adzone_id'],
                'commission_fee' => $v['commission_fee'],
            ];
        }
        $insertId = $model->addAll($order);
        return $insertId;
    }

    public function edit(){
        $model = M(self::T_ORDER);
        $orders = $model->field('id,special_id')->limit(100)->where('state = 1')->select();
        if(!$orders){
            showError(20004,'暂无未匹配的订单');
        }
        $memberId = array_unique(array_column($orders,'special_id'));
        $memberModel = M(self::T_MEMBER);
        $members = $memberModel->field('id,special_id,referee_id,channel_id')
            ->where(['special_id' => ['in',$memberId]])->select();
        if(!$members){
            showError(20004,'未找到会员');
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
                $v['state'] = 2;
            }
        });
        $res = saveAll($orders,self::T_ORDER);
        if(!$res){
            showError(20004,'匹配失败');
        }
        returnResult();
    }
}