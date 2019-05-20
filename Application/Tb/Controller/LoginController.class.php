<?php
namespace Tb\Controller;
use Think\Controller;
class LoginController extends Controller {

	const T_MEMBER= 'tr_member';

    public function wechat(){
    	if (IS_POST) {
    		$data['openid'] = $_POST['openid'];
	    	$model = M(self::T_MEMBER);
	    	if($member = $model->where($data)->field('id,phone,password')->find()){
	    		$return['code'] = 1;
	    		$return['data'] = encodeing(json_encode($member));
	    		ajaxReturn($return);
	    	}else{
	    		$return['code'] = 300;
	    		$return['data'] = '您还没有注册或未绑定微信哦！';
	    		ajaxReturn($return);
	    	}
    	}else{
    		$return['code'] = 400;
    		$return['data'] = '非法请求';
    		ajaxReturn($return);
    	}
	    	
    	
    }

    public function smsloginsend(){
    	$phone = $_GET['phone'];
    	$model = M(self::T_MEMBER);
    	$where['phone'] = $phone;
    	if($member = $model->where($where)->find()){
	    	if(S($phone.'_'.smslogincode)&&time()-S($phone.'_'.smslogintime) < 180){
	                $return['code'] = '10004';
		    		$return['data'] = '该手机号码存在未使用验证码，请勿频繁发送';
		    		ajaxReturn($return);
	        }else{
	        	$res = sendsms($phone);
		    	if($res['code']=='10000'){
		    		S($phone.'_'.smslogincode,$res['smscode'],180);
	        		S($phone.'_'.smslogintime,time(),180);
		    		$return['code'] = '10000';
		    		$return['data'] = '发送成功';
		    		ajaxReturn($return);
		    	}else{
		    		$return['code'] = '10002';
		    		$return['data'] = '发送失败';
		    		ajaxReturn($return);
		    	}
	        }
    	}else{
    		$return['code'] = '10005';
    		$return['data'] = '该号码尚未注册,请输入注册时填写的手机号码';
    		ajaxReturn($return);
    	}
    }

    public function smslogin(){
    	if (IS_GET) {
    		$smscode = $_GET['smscode'];
    		$phone = $_GET['phone'];
    		if(S($phone.'_'.smslogincode)&&time()-S($phone.'_'.smslogintime) < 180){
	            if($smscode == S($phone.'_'.smslogincode)){
	            	$where['phone'] = $phone;
	            	$model = M(self::T_MEMBER);
	            	$member = $model->where($where)->field('id,phone,password')->find();
	            	$return['code'] = '10000';
		    		$return['data'] = encodeing(json_encode($member));
		    		ajaxReturn($return);
	            	
	            }else{
	            	$return['code'] = '10003';
		    		$return['data'] = '验证码不正确，请重新输入';
		    		ajaxReturn($return);
	            }
	        }else{
	        	$return['code'] = '10002';
	    		$return['data'] = '验证码已过期，请重新获取';
	    		ajaxReturn($return);
	        }
    	}else{
    		$return['code'] = 400;
    		$return['data'] = '非法请求';
    		ajaxReturn($return);
    	}
    }

    public function sjlogin(){
    	if (IS_POST) {
    		$where['password'] = md5($_POST['password']);
    		$where['phone'] = $_POST['phone'];

        	$model = M(self::T_MEMBER);
        	$member = $model->where($where)->field('id,phone,password')->find();

    		if($member){
	            $return['code'] = '10000';
		    		$return['data'] = encodeing(json_encode($member));
		    		ajaxReturn($return);
	        }else{
	        	$return['code'] = '10002';
	    		$return['data'] = '手机号或密码不正确，请检查后重新输入';
	    		ajaxReturn($return);
	        }
    	}else{
    		$return['code'] = 400;
    		$return['data'] = '非法请求';
    		ajaxReturn($return);
    	}
    }
    public function test(){
    	$model = M(self::T_MEMBER);
        $edit = $model->where(['id'=>'2'])->find();
        $edit['wx_nickname'] = base64_decode($edit['wx_nickname']);
        dump($edit);
    }
    

    
}