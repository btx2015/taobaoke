<?php
namespace Tb\Controller;
use Think\Controller;
class NavController extends Controller {

    const T_MANAGE_NAV = 'tr_manage_nav';

    const T_MANAGE_TOPNAV = 'tr_manage_topnav';

    public function nav(){
    	$model = M(self::T_MANAGE_NAV);
    	$nav = $model->where(['state'=>'1'])->order('sort')->select();
        ajaxReturn($nav);
    }

    public function ziyingnav(){
        $model = M(self::T_MANAGE_NAV);
        $nav = $model->where(['state'=>'1'])->order('sort')->select();
        ajaxReturn($nav);
    }

    public function cate(){

    	$model = M(self::T_MANAGE_TOPNAV);
    	$nav = $model->where(['state'=>'1'])->order('sort')->select();
    	$data = array();
    	foreach ($nav as $key => $value) {
    		$v['id'] = 'index-'.$value['id'];
    		$v['url'] = $value['url'];
    		
			$v['extras']['type'] = $value['type'];
			$v['extras']['parm'] = $value['parm'];
    		array_push($data, $v);
    	}
    	$res['nav'] = $nav;
    	$res['data'] = $data;
    	
    	ajaxReturn($res);
    }

}