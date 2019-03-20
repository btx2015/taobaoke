<?php

namespace Admin\Controller\Manage;

use Admin\Controller\CommonController;

class BannerController extends CommonController
{

    const T_BANNER = "tr_manage_banner";

    public function index(){
        $model = M(self::T_BANNER);
        $banners = $model->select();
        $this->assign('banner',$banners);
        $this->display();
    }

}