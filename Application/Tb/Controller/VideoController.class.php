<?php
namespace Tb\Controller;
use Think\Controller;
class VideoController extends Controller {

    const T_VIDEO_BANNER = 'tr_video_banner';

    public function banner(){
    	$model = M(self::T_VIDEO_BANNER);
    	$banner = $model->where(['state'=>'1'])->order('sort')->select();
        ajaxReturn($banner);
    }

    public function indexggbanner(){
    	$model = M(self::T_MANAGE_BANNER);
    	$banner = $model->where(['state'=>'1'])->order('sort')->select();
        ajaxReturn($banner);
    }

    public function ggpb(){
        $data[0]['match'] = '.*nijiua.*';
        $data[0]['redirect'] = '_www/js/gg.js';
        // "{match: '.*nijiua.*',redirect: '_www/js/gg.js'}";
        ajaxReturn($data);

    }

}