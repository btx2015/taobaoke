<?php

namespace Admin\Controller\Manage;

use Admin\Controller\CommonController;

class BannerController extends CommonController
{

    const T_BANNER = "tr_manage_banner";

    public function index(){
        $where = validate([
            'title' => [[]],
            'state' => [['in'=>[1,2]]]
        ]);
        if(!is_array($where))
            showError(10006);
        $model = M(self::T_BANNER);
        $banners = $model->where($where)->select();
        $banners = handleRecords([
            'state'           => ['translate','state','state_str'],
            'created_at'      => ['time','Y-m-d H:i:s','created_at_str'],
        ],$banners);
        $this->assign('banners',$banners);
        $this->display();
    }


    public function edit(){

    }

    public function del(){

    }
}