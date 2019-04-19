<?php

namespace Admin\Controller\Goods;

use Admin\Controller\CommonController;
use Think\Log;

class ItemController extends CommonController
{

    const T_ITEM = 'tr_items';

    const T_CATE = 'tr_category';

    const T_SYNC = 'tr_items_sync';

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
                    'fqcat' => ['array_walk',$cate,'fqcat_str'],
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
        if(IS_POST){
            $time = time();
            $model = M(self::T_SYNC);
            $getItems = S('getItems');
            if($getItems){
                $sync = $model->where(['id'=>$getItems['id'],'type'=>1])->find();
                if($sync['state'] == 2){
                    S('getItems',null);
                }else{
                    if($time - $getItems['time'] > 300)
                        returnResult($sync['id']);
                    else
                        showError(20004,'商品同步中');
                }
            }
            $today = strtotime(date('Y-m-d',$time));
            $sync = $model->where(['type'=>1,'created_at'=>['egt',$today]])->find();
            $start = $sync ? $sync['end'] : 0;
            $end = date('H',$time);
            $id = $model->save([
                'start' => $start,
                'end' => $end,
                'created_at' => $time
            ]);
            if(!$id)
                showError(20001,'创建同步申请单失败');

            S('getItems',[
                'time' => $time,
                'id' => $id,
                'start' => $start,
                'end' => $end,
                'page' => 1
            ]);
        }else{
            $id = I('get.id');
            $getItems = S('getItems');
            if(!$getItems)
                showError(20004,'同步完成');
            if($id != $getItems['id'])
                showError(10006,'商品同步中');
            $start = $getItems['start'];
            $end = $getItems['end'];
            $pageNo = $getItems['page'];
            $data = $this->api_request('v2.api.haodanku.com/timing_items',[
                'back'   => 100,
                'min_id' => $pageNo,
                'start'  => $start,
                'end'    => $end,
            ]);
            if(empty($data)){
                $res = M(self::T_SYNC)->where(['id'=>$id])->setField('state',2);
                if(!$res)
                    showError(20002,'同步申请单状态更新失败');
                S('getItems',null);
                showError(20004,'同步完成');
            }else{
                $this->add_data($data);
                $pageNo ++;
            }
            S('getItems',[
                'id'    => $id,
                'start' => $start,
                'end'   => $end,
                'page'  => $pageNo,
                'time'  => time()
            ]);
        }

        returnResult($id);
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
        if(IS_POST){
            $getItems = S('updateItems');
            if($getItems){
                if(time() - $getItems['time'] > 300)
                    returnResult($getItems['no']);
                else
                    showError(20004,'商品更新中');
            }
            returnResult(uniqid());
        }else{
            $getItemsNo = I('post.no');
            $getItems = S('updateItems');
            if(!$getItems)
                showError(20004);
            if($getItemsNo != $getItems['no'])
                showError(10006,'商品更新中');
            $pageNo = $getItems['page'];
            $data = $this->api_request('v2.api.haodanku.com/update_item',[
                'back'   => 100,
                'min_id' => $pageNo,
            ]);
            if(empty($data)){
                S('updateItems',null);
                showError(20004,'更新完成');
            }else{
                $this->update_data($data);
                $pageNo ++;
            }
            S('updateItems',[
                'no'    => $getItemsNo,
                'time'  => time(),
                'page'  => $pageNo
            ]);
            returnResult();
        }
    }

    private function update_data($data){
        $res = saveAll($data,self::T_ITEM,'product_id');
        if(!$res){
            Log::record(json_encode($data),'ERR');
            showError(20001);
        }
        return true;
    }

    public function del(){
        if(IS_POST){
            $time = time();
            $model = M(self::T_SYNC);
            $getItems = S('removeItems');
            if($getItems){
                $sync = $model->where(['id'=>$getItems['id'],'type'=>2])->find();
                if($sync['state'] == 2){
                    S('removeItems',null);
                }else{
                    if($time - $getItems['time'] > 300)
                        returnResult($sync['id']);
                    else
                        showError(20004,'商品同步中');
                }
            }
            $today = strtotime(date('Y-m-d',$time));
            $sync = $model->where(['type'=>2,'created_at'=>['egt',$today]])->find();
            $start = $sync ? $sync['end'] : 0;
            $end = date('H',$time);
            $id = $model->save([
                'start' => $start,
                'end' => $end,
                'created_at' => $time
            ]);
            if(!$id)
                showError(20001,'创建同步申请单失败');

            S('removeItems',[
                'time' => $time,
                'id' => $id,
                'start' => $start,
                'end' => $end,
                'page' => 1
            ]);
        }else{
            $id = I('get.id');
            $getItems = S('removeItems');
            if(!$getItems)
                showError(20004,'同步完成');
            if($id != $getItems['id'])
                showError(10006,'商品同步中');
            $start = $getItems['start'];
            $end = $getItems['end'];
            $pageNo = $getItems['page'];
            $data = $this->api_request('v2.api.haodanku.com/get_down_items',[
                'back'   => 100,
                'min_id' => $pageNo,
                'start'  => $start,
                'end'    => $end,
            ]);
            if(empty($data)){
                $res = M(self::T_SYNC)->where(['id'=>$id])->setField('state',2);
                if(!$res)
                    showError(20002,'同步申请单状态更新失败');
                S('removeItems',null);
                showError(20004,'同步完成');
            }else{
                $this->delete_data($data);
                $pageNo ++;
            }
            S('removeItems',[
                'id'    => $id,
                'start' => $start,
                'end'   => $end,
                'page'  => $pageNo,
                'time'  => time()
            ]);
        }

        returnResult($id);
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