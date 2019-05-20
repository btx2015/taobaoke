<?php
namespace Tb\Controller;
use Think\Controller;
class BannerController extends Controller {

    const T_MANAGE_BANNER = 'tr_manage_banner';

    public function banner(){
    	$model = M(self::T_MANAGE_BANNER);
    	$banner = $model->where(['state'=>'1'])->order('sort')->select();
        ajaxReturn($banner);
    }

    public function indexggbanner(){
    	$model = M(self::T_MANAGE_BANNER);
    	$banner = $model->where(['state'=>'1'])->order('sort')->select();
        ajaxReturn($banner);
    }

    public function indexad(){
        $model = M(self::T_MANAGE_BANNER);
        $banner = $model->where(['state'=>'1'])->order('sort')->select();
        ajaxReturn($banner);
    }

    public function indexsgqg(){
        $model = M(self::T_MANAGE_BANNER);
        $banner = $model->where(['state'=>'1'])->order('sort')->select();
        ajaxReturn($banner);
    }

    public function adinfo(){
        $banner['img'] = '/Uploads/nav/2019-05-12/5cd7b72e74978.png';
        $banner['url'] = '_www/index-2.html';
        $banner['type'] = '2';
        $banner['parm'] = '广告';
        ajaxReturn($banner);
    }

}