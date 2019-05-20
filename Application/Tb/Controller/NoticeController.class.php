<?php
namespace Tb\Controller;
use Think\Controller;
class NoticeController extends Controller {

    const T_MANAGE_NOTICE = 'tr_manage_notice';

    public function notice(){
    	$model = M(self::T_MANAGE_NOTICE);
    	$notice = $model->where(['state'=>'1'])->order('sort')->select();
        ajaxReturn($notice);
    }

    public function noticedetail(){
    	$model = M(self::T_MANAGE_NOTICE);
    	if (IS_GET&&!is_null($_GET['id'])) {
    		$notice = $model->where(['state'=>'1','id'=>$_GET['id']])->find();
        	$this->assign('notice',$notice);
        	$this->display();
    	}else{
    		$this->display();
    	}
    	
    }

}