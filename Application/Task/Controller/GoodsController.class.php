<?php

namespace Task\Controller;

class GoodsController extends CommonController
{

    const T_ITEM = 'tr_items';

    const ADD_URL = 'v2.api.haodanku.com/itemlist';

    const UPDATE_URL = 'v2.api.haodanku.com/update_item';

    const DEL_URL = 'v2.api.haodanku.com/get_down_items';

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

    public function add_goods(){
        $log = 'items.add';
        writeLog('商品上新开始',$log,'DEBUG');
        $pageNo = 1;
        while(true){
            echo $pageNo.PHP_EOL;
            $data = $this->api_request(self::ADD_URL,[
                'nav'  => 3,
                'back' => 100,
                'min_id' => $pageNo
            ]);
            if(empty($data['data']))
                break;
            $this->add_data($data['data']);
            $pageNo = $data['min_id'];
        }
        writeLog('商品上新结束',$log,'DEBUG');
        die('create success'.PHP_EOL);
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

    private function add_data($data){
        $model = M(self::T_ITEM);
        $time = time();
        array_walk($data,function(&$v)use($time){
            $v['created_at'] = $time;
        });
        $insertId = $model->addAll($data);
        if(!$insertId){
            $log = 'items.add';
            writeLog('商品数据添加失败，原因：'.$insertId,$log,'ERROR');
            exit('create error');
        }
        return true;
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
        writeLog('商品失效 itemid:'.$id,$log,'DEBUG');
        $res = saveAll($data,self::T_ITEM,'itemid');
        if($res === false){
            $log = 'items.delete';
            writeLog('商品失效更新失败。',$log,'ERROR');
            exit('create error');
        }
        return $res;
    }
}