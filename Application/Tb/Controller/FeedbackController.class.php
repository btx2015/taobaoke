<?php
namespace Tb\Controller;
use Think\Controller;
class FeedbackController extends Controller {

    public function feedback(){
    	$data['data'] = $_POST;
    	$data['ret'] = 0;
    	$data['desc'] = 'Success';
    	ajaxReturn($data);
    }

    public function cate(){
    	$model = M(self::T_ARTICLE_CATE);
    	$cate = $model->field('id,name,pid,sort')->select();
    	returnResult($cate);
    }
}