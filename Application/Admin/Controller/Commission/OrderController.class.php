<?php

namespace Admin\Controller\Commission;

use Admin\Controller\CommonController;
use Think\Log;

class OrderController extends CommonController
{

    const T_ORDER = 'tr_commission_order';

    const T_MEMBER = 'tr_member';

    const T_CHANNEL = 'tr_channel';

    public function index(){
        if(IS_POST){
            $model = M(self::T_ORDER);
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'order_sn'    => [[],false,true,['like','order_sn']],
                'state'       => [['in'=>[1,2,3]],false,true,['eq','state']],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ],false);
            $list = $model->where($where)->page($pageNo,$pageSize)->order('state asc')->select();
            $members = $channels = [];
            if($list){
                $memberId = [];
                $fields = ['user_id','referee_id','grand_id'];
                foreach($fields as $v){
                    $memberId = array_merge($memberId,array_filter(array_unique(array_column($list,$v)),function($var){
                        return $var ? 1 : 0;
                    }));
                }
                $memberId = array_unique($memberId);
                if($memberId){
                    $members = M(self::T_MEMBER)->field('id,username')->where(['id' => ['in',$memberId]])->select();
                    if($members){
                        $members = array_column($members,'username','id');
                    }
                }
                $channelId = array_column($list,'channel_id');
                $channels = M(self::T_CHANNEL)->field('id,name')->where(['id'=>['in',$channelId]])->select();
                if($channels){
                    $channels = array_column($channels,'name','id');
                }
            }
            returnResult([
                'list' => handleRecords([
                    'user_id'    => ['array_walk',$members,'user_id_str'],
                    'referee_id' => ['array_walk',$members,'referee_id_str'],
                    'grand_id'   => ['array_walk',$members,'grand_id_str'],
                    'channel_id' => ['array_walk',$channels,'channel_id_str'],
                    'state'      => ['translate','order_state','state_str'],
                ],$list),
                'total' => $model->where($where)->count()
            ]);
        }else{
            $this->display();
        }
    }

    public function add(){
        $startTime = I('post.time');
        $pageNo = I('post.page');
        if(!$startTime){
            $settle = S('settleInfo');
            if($settle){
                $startTime = $settle['time'];
                $pageNo = $settle['page'];
            }else{
                $startTime = strtotime(date('Y-m-d'));
                $pageNo = 1;
            }
        }
        $endTime = strtotime(date('Y-m-d',strtotime('+1 day')));
        if($startTime < $endTime){
            $data = $this->api_request($startTime,$pageNo);
            if(empty($data)){
                $startTime += 1200;
                $pageNo = 1;
            }else{
                $this->add_data($data);
                $pageNo ++;
            }
            S('settleInfo',[
                'time' => $startTime,
                'page' => $pageNo
            ]);
            returnResult();
        }else{
            S('settleInfo',null);
            showError(20004,'暂无未同步的订单');
        }
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
        if(!$insertId){
            Log::record(json_encode($order),'ERR');
            showError(20001);
        }
        return true;
    }

    /**
     * @param string $startTime 时间戳 订单查询开始时间
     * @param int $pageNo 页数
     * @return mixed
     * 接口文档
     * https://developer.alibaba.com/docs/api.htm?spm=a219a.7395905.0.0.29e675fex8tgK4&apiId=24527
     */
    private function api_request($startTime,$pageNo){
        $appSecret = '43967a2c75599e4027f7b5ff698b604b';
        $params = [
            'method' => 'taobao.tbk.order.get',
            'app_key' => '25521171',
            'sign_method' => 'md5',
            'timestamp' => date('Y-m-d H:i:s',time()),
            'format' => 'json',
            'v' => '2.0',
            'fields' => 'trade_id,total_commission_fee,special_id,adzone_id,relation_id',
            'start_time' => date('Y-m-d H:i:s',$startTime),
            'span' => 1200,
            'page_no' => $pageNo,
            'page_size' => 100,
            'tk_status' => 3,
            'order_query_type' => 'settle_time',
            'order_scene' => 3
        ];
        ksort($params);
        $string = '';
        foreach($params as $k => $v){
            $string .= $k.$v;
        }
        $sign = md5($appSecret.$string.$appSecret);
        $params['sign'] = strtoupper($sign);
        $res = curlRequest('gw.api.taobao.com/router/rest',$params);
        $result = json_decode($res,true);
        if(isset($result['error_response']) || !isset($result['tbk_order_get_response'])){
            Log::record($res,'ERR');
            showError(10005);
        }
        return $result['tbk_order_get_response']['results'];
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