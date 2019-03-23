<?php
/**
 * Created by PhpStorm.
 * User: leasin
 * Date: 2019/3/23
 * Time: 13:53
 */

namespace Admin\Controller;


use Think\Controller;

class EmptyController extends Controller
{
    public function _empty(){
        $this->redirect('System/Public/login');
    }
}