<?php
namespace Tb\Controller;
use Think\Controller;
Vendor('taobao.TopSdk');
// Vendor('taobao.RSACrypto');
class GuideController extends Controller {

	
	const T_MANAGE_GUIDE = 'tr_manage_guide';


	public function newguide()
	{	

		$model = M(self::T_MANAGE_GUIDE);
		$data = $model->where(['state'=>'1'])->order('sort')->select();
		returnResult($data);

	}
	
	public function detail()
	{	

		$model = M(self::T_MANAGE_GUIDE);
		$data = $model->where(['state'=>'1','id'=>$_GET['id']])->find();
		// dump($_GET);
		// dump($data);
		
		$this->assign('data',$data);
    	$this->display();

	}
   

    
}