<?php
namespace Tb\Controller;
use Think\Controller;
class UpdateController extends Controller {
	const T_ARTICLE = 'tr_article';
	const T_ARTICLE_CATE = 'tr_article_cate';
    public function check(){
    	if ($_GET['appid']=='H55ED9FEE'&&$_GET['version']!='1.0.5') {
    		$data['status'] = 1;
    	}
    	
    	$data['appid'] = "H55ED9FEE";
    	$data['version'] = "1.0.5";
    	$data['title'] = "版本更新";
    	$data['note'] = "修复部分已知bug；";
    	$data['url'] = "http://jx.tangrenjuhui.com/com.tangrenjuhui.trjh.1.0.5.apk";
    	ajaxReturn($data);
    }

   
}