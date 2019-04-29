<?php
/**
 * Created by PhpStorm.
 * User: leasin
 * Date: 2019/4/25
 * Time: 10:43
 */

namespace Task\Controller;

use Think\Log;

class GoodsController extends CommonController
{

    const T_ITEM = 'tr_items';

    const ADD_URL = 'v2.api.haodanku.com/itemlist';

    const UPDATE_URL = 'v2.api.haodanku.com/update_item';

    const DEL_URL = 'v2.api.haodanku.com/get_down_items';

    private function api_request($url,$params){
        $params['apikey'] = 'jifenbao';
        foreach($params as $name => $value){
            $url .= '/'.$name.'/'.$value;
        }
        $res = curlRequest($url,$params,'GET');
        $result = json_decode($res,true);
        if(!isset($result['code']) || !isset($result['data'])){
            Log::record('好单库接口请求失败，接口：'.$url.'；返回结果：'.$res,'ERR');
            exit();
        }
        if($result['code'] != 1){
            Log::record('好单库接口请求错误，接口：'.$url.'；错误码：'.$result['code'].'；错误信息：'.$result['data'],'ERR');
            $result['data'] = [];
        }
        return $result;
    }

    public function add_goods(){
        Log::record('商品上新开始','DEBUG');
        $pageNo = 1;
        $request = true;
        while($request){
            echo $pageNo.PHP_EOL;
            $data = $this->api_request(self::ADD_URL,[
                'nav'  => 3,
                'back' => 1000,
                'min_id' => $pageNo
            ]);
            if(empty($data['data']))
                $request = false;
            $this->add_data($data['data']);
            $pageNo = $data['min_id'];
        }
        Log::record('商品上新结束','DEBUG');
        echo 'create success'.PHP_EOL;
    }

    public function update_goods(){
        Log::record('商品更新开始','DEBUG');
        $pageNo = 1;
        $request = true;
        $row = 0;
        while($request){
            echo 'mid_id:'.$pageNo.', total:'.$row.PHP_EOL;
            $data = $this->api_request(self::UPDATE_URL,[
                'sort' => 1,
                'back' => 1000,
                'min_id' => $pageNo
            ]);
            if(empty($data['data']))
                $request = false;
            $row += $this->update_data($data['data']);
            $pageNo = $data['min_id'];
        }
        Log::record('商品更新结束','DEBUG');
        echo 'update success, total:'.$row.PHP_EOL;
    }

    public function delete_goods(){
        Log::record('商品失效开始','DEBUG');
        $res = 0;
        $end = date('H',time());
        if($end){
            $start = (int)$end - 1;
            $data = $this->api_request(self::DEL_URL,[
                'start' => $start,
                'end' => $end
            ]);
            if($data['data']){
                $res = $this->delete_data($data['data']);
            }
        }
        Log::record('商品失效结束','DEBUG');
        echo 'delete success, total:'.$res.PHP_EOL;
    }

    private function add_data($data){
        $model = M(self::T_ITEM);
        $time = time();
        array_walk($data,function(&$v)use($time){
            $v['created_at'] = $time;
        });
        $insertId = $model->addAll($data);
        if(!$insertId){
            Log::record('商品数据添加失败，原因：'.$insertId,'ERR');
            exit('create error');
        }
        return true;
    }

    private function update_data($data){
        $res = saveAll($data,self::T_ITEM,'product_id');
        if($res === false){
            Log::record('商品数据更新失败','ERR');
            exit('update error');
        }
        return $res;
    }

    private function delete_data($data){
        $remove = [];
        foreach($data as $v){
            $remove[] = [
                'itemid' => $v['itemid'],
                'down_type' => $v['down_type'],
                'down_time' => $v['down_time'],
                'state' => 0
            ];
        }
        $res = saveAll($data,self::T_ITEM,'itemid');
        if($res === false){
            Log::record('商品失效更新失败','ERR');
            exit('create error');
        }
        return $res;
    }
}