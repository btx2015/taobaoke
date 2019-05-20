<?php
namespace Tb\Controller;
use Think\Controller;
class FaqController extends Controller {


	// const T_ARTICLE = 'tr_article';
	const T_MANAGE_FAQ = 'tr_manage_faq';
	const T_MANAGE_FAQ_CATE = 'tr_manage_faq_cate';


    public function answer(){
    	$model = M(self::T_MANAGE_FAQ);
    	$answer = $model->where(['id'=>$_GET['id'],'state'=>'1'])->find();
    	$this->assign('answer',$answer);
    	$this->display();
    }

    public function question(){

		$model = M(self::T_MANAGE_FAQ);
    	$question = $model->where(['cate_id'=>$_GET['id'],'state'=>'1'])->field('id,title,sort')->order('sort')->select();
    	$this->assign('question',$question);
    	$this->display();
    }

    public function faq(){
    	$model = M(self::T_MANAGE_FAQ_CATE);
    	$cate = $model->where(['state'=>'1'])->field('id,name,sort')->select();
    	foreach ($cate as $key => $value) {
    		$models = M(self::T_MANAGE_FAQ);
    		$faq = $models->where(['cate_id'=>$value['id'],'state'=>'1'])->field('id,title,sort')->order('sort')->limit(2)->select();
    		$cate[$key]['child'] = $faq;
    	}
    	returnResult($cate);
    }

    
}