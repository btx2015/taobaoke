<?php

namespace Admin\Controller\Goods;

use Admin\Controller\CommonController;
use Think\Log;

class ItemController extends CommonController
{

    const T_ITEM = 'tr_items';

    const T_CATE = 'tr_category';

    public function index(){
        if(IS_POST){
            $model = M(self::T_ITEM);
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'itemtitle'   => [[],false,true,['like','title']],
                'fqcat'       => [['num']],
            ]);
            $list = $model->where($where)->page($pageNo,$pageSize)->select();
            $cate = [];
            if($list){
                $cateId = array_filter(array_unique(array_column($list,'fqcat')),function($var){
                    return $var ? 1 : 0;
                });
                if($cateId){
                    $cateModel = M(self::T_CATE);
                    $cate = $cateModel->where(['id'=>['in',$cateId]])->select();
                }
                if($cate)
                    $cate = array_column($cate,'name','id');
            }
            returnResult([
                'list' => handleRecords([
                    'fqcat'      => ['array_walk',$cate,'fqcat_str'],
                ],$list),
                'total' => $model->where($where)->count()
            ]);
        }else{
            $model = M(self::T_CATE);
            $data = $model->field('id,name,pid')->where(['state'=>1])->select();
            $parent = cateFormat($data);
            $this->assign('parent',$parent);
            $this->display();
        }
    }

    public function add(){
        $getItemsNo = I('post.no');
        $getItems = S('getItems');
        if($getItems){
            if($getItemsNo != $getItems['no'])
                showError(10006,'商品同步中');
            $start = $getItems['start'];
            $end = $getItems['end'];
            $pageNo = $getItems['page'];
        }else{
            if($getItemsNo)
                showError(10006,'商品同步已结束');
            $start = I('post.start');
            $end = I('post.end');
            if(!$start || !$end)
                showError(10006,'请输入开始和结束时间');
            $pageNo = 1;
        }

        $data = $this->api_request('v2.api.haodanku.com/timing_items',[
            'back' => 100,
            'min_id' => $pageNo,
            'start' => $start,
            'end' => $end,
        ]);
        if(empty($data)){
            showError(20004,'同步完成');
        }else{
            $this->add_data($data);
            $pageNo ++;
        }
        S('getItems',[
            'start' => $start,
            'end' => $end,
            'page' => $pageNo
        ]);
        returnResult();
    }

    private function add_data($data){
        $model = M(self::T_ITEM);
        $time = time();
        array_walk($data,function(&$v)use($time){
            $v['created_at'] = $time;
        });
        $insertId = $model->addAll($data);
        if(!$insertId){
            Log::record(json_encode($data),'ERR');
            showError(20001);
        }
        return true;
    }

    private function api_request($url,$params){
        $params['apikey'] = '';
        $res = curlRequest($url,$params,'GET');
        $result = json_decode($res,true);
        if(isset($result['code']) || !isset($result['data'])){
            Log::record($res,'ERR');
            showError(10005);
        }
        return $result['data'];
    }

    public function edit(){
        $getItemsNo = I('post.no');
        $getItems = S('updateItems');
        if($getItems){
            if($getItemsNo != $getItems['no'])
                showError(10006,'商品更新中');
            $start = $getItems['start'];
            $end = $getItems['end'];
            $pageNo = $getItems['page'];
        }else{
            if($getItemsNo)
                showError(10006,'商品更新已结束');
            $start = I('post.start');
            $end = I('post.end');
            if(!$start || !$end)
                showError(10006,'请输入开始和结束时间');
            $pageNo = 1;
        }

        $data = $this->api_request('v2.api.haodanku.com/update_item',[
            'back' => 100,
            'min_id' => $pageNo,
            'start' => $start,
            'end' => $end,
        ]);
        if(empty($data)){
            showError(20004,'更新完成');
        }else{
            $this->update_data($data);
            $pageNo ++;
        }
        S('updateItems',[
            'start' => $start,
            'end' => $end,
            'page' => $pageNo
        ]);
        returnResult();
    }

    private function update_data($data){
        $res = saveAll($data,self::T_ITEM,'product_id');
        if(!$res){
            Log::record(json_encode($data),'ERR');
            showError(20001);
        }
        return true;
    }

    public function remove(){
        $start = I('post.start');
        $end = I('post.end');
        if(!$start || !$end)
            showError(10006,'请输入开始和结束时间');

        $data = $this->api_request('v2.api.haodanku.com/get_down_items',[
            'start' => $start,
            'end' => $end,
        ]);
        if(empty($data)){
            showError(20004,'更新完成');
        }else{
            $this->delete_data($data);
        }
        returnResult();
    }

    private function delete_data($data){
        $remove = [];
        foreach($data as $v){
            $remove[] = [
                'itemid' => $v['itemid'],
                'down_type' => $v['down_type'],
                'down_time' => $v['down_time'],
                'state' => 2
            ];
        }
        $res = saveAll($data,self::T_ITEM,'itemid');
        if(!$res){
            Log::record(json_encode($data),'ERR');
            showError(20001);
        }
        return true;
    }
}