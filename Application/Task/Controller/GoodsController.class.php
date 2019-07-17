<?php

namespace Task\Controller;

class GoodsController extends CommonController
{

    const T_ITEM = 'tr_items';

    const ADD_URL = 'v2.api.haodanku.com/itemlist';

    const UPDATE_URL = 'v2.api.haodanku.com/update_item';

    const DEL_URL = 'v2.api.haodanku.com/get_down_items';

    const SUPPLY_URL = 'v2.api.haodanku.com/timing_items';

    private function api_request($url,$params){
        $log = 'items.request';
        $params['apikey'] = 'jifenbao';
        foreach($params as $name => $value){
            $url .= '/'.$name.'/'.$value;
        }
        $res = curlRequest($url,$params,'GET');
        $result = json_decode($res,true);
        if(!isset($result['code']) || !isset($result['data'])){
            writeLog('好单库接口请求失败，接口：'.$url.'；返回结果：'.$res,$log,'ERROR');
            exit();
        }
        if($result['code'] != 1){
            writeLog('好单库接口请求错误，接口：'.$url.'；错误码：'.$result['code'].'；错误信息：'.$result['msg'],$log,'ERROR');
            $result['data'] = [];
        }
        return $result;
    }

    public function times_goods(){
        $log = 'items.times';
        writeLog('商品定时拉取开始',$log,'DEBUG');
        $total = 0;
        $end = date('H',time());
        $minId = 1;
        if($end){
            while(true){
                echo 'mid_id:'.$minId.PHP_EOL;
                $start = (int)$end - 1;
                $data = $this->api_request(self::SUPPLY_URL,[
                    'start'  => $start,
                    'end'    => $end,
                    'min_id' => $minId,
                    'back'   => 500
                ]);
                if(empty($data['data']))
                    break;
                $res = $this->add_data($data['data'],$log);
                if($res === false){
                    writeLog('商品定时拉取数据添加失败。数据：'.json_encode($data['data']),$log,'ERROR');
                }else{
                    $total += $res;
                }
                $minId = $data['min_id'];
            }
        }
        writeLog('商品定时拉取开始结束',$log,'DEBUG');
        die('add success, total:'.$total.PHP_EOL);
    }

    public function add_goods(){
        $log = 'items.add';
        writeLog('商品上新开始',$log,'DEBUG');
        $pageNo = 1;
        $total = 0;
        while(true){
            echo $pageNo.PHP_EOL;
            $data = $this->api_request(self::ADD_URL,[
                'nav'  => 3,
                'back' => 100,
                'min_id' => $pageNo
            ]);
            if(empty($data['data']))
                break;
            $res = $this->add_data($data['data'], $log);
            if($res !== false){
                $total += $res;
            }
            $pageNo = $data['min_id'];
        }
        writeLog('商品上新结束，上新'.$total.'条商品',$log,'DEBUG');
        die('create success! Total:'.$total.PHP_EOL);
    }

    public function update_goods(){
        $log = 'items.update';
        writeLog('商品更新开始',$log,'DEBUG');
        $pageNo = 1;
        $row = 0;
        while(true){
            echo 'mid_id:'.$pageNo.', total:'.$row.PHP_EOL;
            $data = $this->api_request(self::UPDATE_URL,[
                'sort' => 1,
                'back' => 1000,
                'min_id' => $pageNo
            ]);
            if(empty($data['data']))
                break;
            $row += $this->update_data($data['data']);
            $pageNo = $data['min_id'];
        }
        writeLog('商品更新结束',$log,'DEBUG');
        die('update success, total:'.$row.PHP_EOL);
    }

    public function delete_goods(){
        $log = 'items.update';
        writeLog('商品失效开始',$log,'DEBUG');
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
        writeLog('商品失效结束',$log,'DEBUG');
        die('delete success, total:'.$res.PHP_EOL);
    }

    private function add_data($data,$log = 'items.add'){
        $model = M(self::T_ITEM);
        $productIds = array_column($data,'product_id');
        $items = $model->field('product_id')->where(['product_id'=>['in',$productIds]])->select();
        if($items)
            $items = array_column($items,'product_id');
        $time = time();
        foreach($data as $k => $v){
            if(in_array($v['product_id'],$items))
                unset($data[$k]);
            else
                $v['created_at'] = $time;
        }
        if($data){
            $insertId = $model->addAll($data);
            if(!$insertId){
                writeLog('商品数据添加失败，原因：'.$insertId,$log,'ERROR');
                echo 'create error'.PHP_EOL;
                return false;
            }
        }
        return count($data);
    }

    private function update_data($data){
        $log = 'items.update';
        $id = json_encode(array_column($data,'product_id'));
        writeLog('商品数据更新 product_id:'.$id,$log,'DEBUG');
        $res = saveAll($data,self::T_ITEM,'product_id');
        if($res === false){
            writeLog('商品数据更新失败。',$log,'ERROR');
            exit('update error');
        }
        return $res;
    }

    private function delete_data($data){
        $log = 'items.delete';
        $remove = $id = [];
        foreach($data as $v){
            $remove[] = [
                'itemid' => $v['itemid'],
                'down_type' => $v['down_type'],
                'down_time' => $v['down_time'],
                'state' => 0
            ];
            $id[] = $v['itemid'];
        }
        writeLog('商品失效 itemid:'.json_encode($id),$log,'DEBUG');
        $res = saveAll($data,self::T_ITEM,'itemid');
        if($res === false){
            $log = 'items.delete';
            writeLog('商品失效更新失败。',$log,'ERROR');
            exit('create error');
        }
        return $res;
    }
}