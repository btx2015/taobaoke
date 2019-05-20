<?php
namespace Tb\Controller;
use Think\Controller;
Vendor('qrcode.phpqrcode');
class PosterController extends Controller {

	const T_MEMBER= 'tr_member';

	public function index()
	{
		ajaxReturn($this->getallheaders());
	}
	function getallheaders() {
		$headers = [];
		foreach ($_SERVER as $name => $value) {
			if (substr($name, 0, 5) == 'HTTP_') {
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
		return $headers;
	}

	private function poster($value)
	{

		$data = $value;
		$errorCorrectionLevel = 'H';//容错级别 
		$matrixPointSize = 4;//生成图片大小 
		\QRcode::png($data, './Public/Tb/img/qrcode.png', $errorCorrectionLevel, $matrixPointSize);
		$logo = './Public/Tb/img/putong.png';//准备好的logo图片 
		$QR = './Public/Tb/img/qrcode.png';//已经生成的原始二维码图 

		if ($logo !== FALSE) { 
			$QR = imagecreatefrompng($QR); 
			$logo = imagecreatefrompng($logo); 
			$QR_width = imagesx($QR);//二维码图片宽度 
			$QR_height = imagesy($QR);//二维码图片高度 

			$logo_width = imagesx($logo);//logo图片宽度 
			$logo_height = imagesy($logo);//logo图片高度 


			$qr_qr_width = $logo_width / 5; 
			$scale = $QR_width/$qr_qr_width; 
			$qr_qr_height = $QR_height/$scale; 
			$from_width = ($logo_width - $qr_qr_width) / 2; 

			// $QR = imagerotate($QR,-15,0);
			imagecopyresampled($logo, $QR, 290, 712, 0, 0, 108, 
			108, $QR_width, $QR_height); 
		} 
		$font = './Public/Tb/img/MONACO.TTF';//字体
		$black = imagecolorallocate($logo, 255, 255, 255);//字体颜色 RGB

		$fontSize = 15;   //字体大小
		$circleSize = 0; //旋转角度
		$left = 160;      //左边距
		$top = 813;       //顶边距

		imagefttext($logo, $fontSize, $circleSize, $left, $top, $black, $font, $value);
		//输出图片 
		imagejpeg($logo, './Public/Tb/img/'.$value.'.jpeg'); 
		return ('/Public/Tb/img/'.$value.'.jpeg');

	}

	private function posters($value)
	{

		$data = $value;
		$errorCorrectionLevel = 'H';//容错级别 
		$matrixPointSize = 4;//生成图片大小 
		\QRcode::png($data, './Public/Tb/img/qrcode.png', $errorCorrectionLevel, $matrixPointSize);
		$logo = './Public/Tb/img/muqinjie.png';//准备好的logo图片 
		$QR = './Public/Tb/img/qrcode.png';//已经生成的原始二维码图 

		if ($logo !== FALSE) { 
			$QR = imagecreatefrompng($QR); 
			$logo = imagecreatefrompng($logo); 
			$QR_width = imagesx($QR);//二维码图片宽度 
			$QR_height = imagesy($QR);//二维码图片高度 

			$logo_width = imagesx($logo);//logo图片宽度 
			$logo_height = imagesy($logo);//logo图片高度 


			$qr_qr_width = $logo_width / 5; 
			$scale = $QR_width/$qr_qr_width; 
			$qr_qr_height = $QR_height/$scale; 
			$from_width = ($logo_width - $qr_qr_width) / 2; 

			// $QR = imagerotate($QR,-15,0);
			imagecopyresampled($logo, $QR, 290, 712, 0, 0, 108, 
			108, $QR_width, $QR_height); 
		} 
		$font = './Public/Tb/img/MONACO.TTF';//字体
		$black = imagecolorallocate($logo, 255, 255, 255);//字体颜色 RGB

		$fontSize = 15;   //字体大小
		$circleSize = 0; //旋转角度
		$left = 160;      //左边距
		$top = 813;       //顶边距

		imagefttext($logo, $fontSize, $circleSize, $left, $top, $black, $font, $value);
		//输出图片 
		imagejpeg($logo, './Public/Tb/img/'.$value.'s.jpeg'); 
		return ('/Public/Tb/img/'.$value.'s.jpeg');

	}

	public function getposter()
	{
		if ($_REQUEST['token']) {
			$txt = decodeing($_REQUEST['token']);
			if ($txt) {

				$where = json_decode($txt);
				$model = M(self::T_MEMBER);
				$member = $model->where($where)->find();
				if($member){
					$poster[0] = $this->poster($member['invitecode']);
					$poster[1] = $this->posters($member['invitecode']);
					returnResult($poster);
				}else{
					showError(20004);
				}
			}else{
				showError(10006);
			}
		}else{
			showError(10005);
		}
	}


	public function getkouling()
	{
		if ($_REQUEST['token']) {
			$txt = decodeing($_REQUEST['token']);
			if ($txt) {

				$where = json_decode($txt);
				$model = M(self::T_MEMBER);
				$member = $model->where($where)->find();
				if($member){
					$data= '邀请您加入唐人聚惠，自动搜索淘宝天猫优惠券！
-------------
下载链接：http://www.tangrenjuhui.com
-------------
先领券，再购物，更划算！
打开唐人聚惠，注册领取优惠券';
					returnResult($data);
				}else{
					showError(20004);
				}
			}else{
				showError(10006);
			}
		}else{
			showError(10005);
		}
	}
}