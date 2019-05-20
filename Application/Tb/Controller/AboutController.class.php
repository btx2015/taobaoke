<?php
namespace Tb\Controller;
use Think\Controller;
class AboutController extends Controller {

    const T_MANAGE_BANNER = 'tr_manage_banner';

    public function privacy(){
    	$this->display('privacy');
    }

    public function registration(){
    	$this->display('privacy');
    }


    public function transaction(){
    	$this->display('privacy');
    }

    

}