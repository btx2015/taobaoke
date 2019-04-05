<?php

namespace Admin\Controller\Product;

use Admin\Controller\CommonController;

class InfoController extends CommonController
{

    const T_PRODUCT = 'tr_product';

    const T_CATE = 'tr_category';

    public function index(){
        if(IS_POST){
            $model = M(self::T_PRODUCT);
            list($where,$pageNo,$pageSize) = before_query([
                'page'        => [['num'],1],
                'rows'        => [['num'],10],
                'title'       => [[],false,true,['like','title']],
                'cate_id'     => [['num']],
                'state'       => [['in'=>[1,2]],false,true,['eq','state']],
                'create_from' => [['time'],false,true,['egt','created_at']],
                'create_to'   => [['time'],false,true,['elt','created_at']],
            ]);
            $list = $model->where($where)->page($pageNo,$pageSize)->select();
            $cate = [];
            if($list){
                $cateId = array_filter(array_unique(array_column($list,'cate_id')),function($var){
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
                    'cate_id'    => ['array_walk',$cate,'cate_id_str'],
                    'state'      => ['translate','state','state_str'],
                    'start_time' => ['time','Y-m-d','start_time_str'],
                    'end_time'   => ['time','Y-m-d','end_time_str'],
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
        $model = M(self::T_PRODUCT);
        $rule = [
            'product_sn'   => [[],true],
            'title'        => [[],true],
            'brief'        => [[],true],
            'cate_id'      => [['num'],true],
            'price'        => [['num'],true],
            'coupon_price' => [['num'],true],
            'real_price'   => [['num']],
            'start_time'   => [['time']],
            'end_time'     => [['time']],
            'total_amount' => [['num'],true],
            'amount'       => [['num'],true],
            'used_amount'  => [[]],
            'url'          => [[],true],
            'coupon_url'   => [[],true],
            'sort'         => [['num'],true],
            'sale_num'     => [['num'],true],
        ];

        $data = beforeSave($model,$rule,['product_sn','title','coupon_url']);

        if(!isset($data['real_price']))
            $data['real_price'] = $data['price'] - $data['coupon_price'];

        if(!isset($data['used_amount']))
            $data['used_amount'] = $data['total_amount'] - $data['amount'];

        $data['created_at'] = time();
        $insertId = $model->add($data);
        if(!$insertId)
            showError(20001);//创建失败
        returnResult();
    }

    public function edit(){
        $model = M(self::T_PRODUCT);
        if(IS_POST){
            $rule = [
                'id'           => [['num'],true,false],
                'product_sn'   => [[]],
                'title'        => [[]],
                'brief'        => [[]],
                'cate_id'      => [['num']],
                'price'        => [['num']],
                'coupon_price' => [['num']],
                'real_price'   => [['num']],
                'start_time'   => [['time']],
                'end_time'     => [['time']],
                'total_amount' => [['num']],
                'amount'       => [['num']],
                'used_amount'  => [[]],
                'url'          => [[]],
                'coupon_url'   => [[]],
                'sort'         => [['num']],
                'sale_num'     => [['num']],
                'state'        => [['in' => [1,2,3]]]
            ];
            $data = beforeSave($model,$rule,['id','product_sn','title','coupon_url']);

            $res = $model->save($data);
            if($res === false)
                showError(20002);//更新失败

            returnResult();
        }else{
            $id = I('get.id');
            $user = $model->where('id ='.$id)->find();
            if(!$user)
                showError(20004);//不存在
            $user['start_time'] = date('Y-m-d',$user['start_time']);
            $user['end_time'] = date('Y-m-d',$user['end_time']);
            returnResult($user);
        }
    }

    public function del(){
        $rule = ['id' => [[],true,false]];
        $data = validate($rule);
        if(!is_array($data))
            showError(10006);//参数错误

        $model = M(self::T_PRODUCT);
        $res = $model->where(['id'=>['in',$data['id']]])->setField('state',3);
        if($res === false)
            showError(20002);
        returnResult();
    }
}