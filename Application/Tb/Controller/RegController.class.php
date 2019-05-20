<?php
namespace Tb\Controller;
use Think\Controller;
class RegController extends Controller {

	const T_MEMBER= 'tr_member';

    public function getmember(){
    	// echo alphaID('pXb8XZ',true,false,'tangrenjuhui');
    	// echo alphaID('13388888888',false,false,'tangrenjuhui');die;
    	if (IS_POST) {
    		if(preg_match('/[a-zA-Z]/',$_POST['num'])){
	    		$data['invitecode'] = $_POST['num'];
	    	}else if(is_numeric($_POST['num'])){
	    		$data['phone'] = $_POST['num'];
	    	}
	    	// ajaxReturn($data);die;
	    	$model = M(self::T_MEMBER);
	    	if($member = $model->where($data)->find()){
	    		$return['code'] = 1;
	    		$return['data'] = $member;
	    		ajaxReturn($return);
	    	}else{
	    		$return['code'] = 300;
	    		$return['data'] = '手机号或邀请码错误';
	    		ajaxReturn($return);
	    	}
    	}else{
    		$return['code'] = 400;
    		$return['data'] = '非法请求';
    		ajaxReturn($return);
    	}
	    	
    	
    }

    public function send(){
    	$phone = $_GET['phone'];
    	if(S($phone.'_'.code)&&time()-S($phone.'_'.time) < 180){
                $return['code'] = '10004';
	    		$return['data'] = '该手机号码存在未使用验证码，请勿频繁发送';
	    		ajaxReturn($return);
        }else{
        	$res = sendsms($phone);
	    	if($res['code']=='10000'){
	    		S($phone.'_'.code,$res['smscode'],180);
        		S($phone.'_'.time,time(),180);
	    		$return['code'] = '10000';
	    		$return['data'] = '发送成功';
	    		ajaxReturn($return);
	    	}else{
	    		$return['code'] = '10002';
	    		$return['data'] = '发送失败';
	    		ajaxReturn($return);
	    	}
        }
    }

    public function smscode(){
    	$phone = $_GET['phone'];
    	$smscode = $_GET['smscode'];
        // $num = $_GET['num'];
        if(preg_match('/[a-zA-Z]/',$_GET['num'])){
            $where['invitecode'] = $_GET['num'];
        }else if(is_numeric($_GET['num'])){
            $where['phone'] = $_GET['num'];
        }
        // ajaxReturn($_GET);die;
    	if(S($phone.'_'.code)&&time()-S($phone.'_'.time) < 180){
            if($smscode == S($phone.'_'.code)){
            	$model = M(self::T_MEMBER);
                if ($model->where(['phone'=>$phone])->find()) {
                    $return['code'] = '10005';
                    $return['data'] = '注册成功或已经注册，请尝试登录';
                }else{

                    
                    $referee = $model->where($where)->find();
                    if($referee){
                        $data['referee_id'] = $referee['id'];
                        $grand = $model->where(['id'=>$referee['referee_id']])->find();
                        if($grand){
                            $data['grand_id'] = $grand['id'];
                        }
                    }
                    $data['phone'] = $phone;
                    $data['username'] = $phone;
                    $data['name'] = '未设置昵称';
                    $data['password'] = md5($phone);
                    $data['created_at'] = time();
                    $data['level'] = '0';
                    $data['avatar'] = 'http://jx.tangrenjuhui.com/Public/Tb/img/logo.png';
                    $data['invitecode'] = alphaID($phone,false,false,'tangrenjuhui');;
                    $add = $model->add($data);
                    if ($add) {
                        $return['code'] = '10000';
                        $return['data'] = '注册成功';
                    }else{
                        $return['code'] = '10004';
                        $return['data'] = '网络错误，请稍后重试';
                    }
                }

                
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
    }

    public function forgotsend(){
    	$phone = $_GET['phone'];
    	$model = M(self::T_MEMBER);
    	$where['phone'] = $phone;
    	if($member = $model->where($where)->find()){
	    	if(S($phone.'_'.forgotcode)&&time()-S($phone.'_'.forgottime) < 180){
	                $return['code'] = '10004';
		    		$return['data'] = '该手机号码存在未使用验证码，请勿频繁发送';
		    		ajaxReturn($return);
	        }else{
	        	$res = sendsms($phone);
		    	if($res['code']=='10000'){
		    		S($phone.'_'.forgotcode,$res['smscode'],180);
	        		S($phone.'_'.forgottime,time(),180);
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

    public function forgot(){
    	$phone = $_POST['phone'];
    	$smscode = $_POST['smscode'];
    	$password = md5($_POST['password']);
    	if(S($phone.'_'.forgotcode)&&time()-S($phone.'_'.forgottime) < 180){
            if($smscode == S($phone.'_'.forgotcode)){
            	$where['phone'] = $phone;
            	$model = M(self::T_MEMBER);
            	$member = $model->where($where)->find();
            	if ($password!=$member['password']) {
            		$res = $model->where($where)->save(['password'=>$password]);
            		if ($res) {
            			$return['code'] = '10000';
			    		$return['data'] = '修改成功!';
			    		ajaxReturn($return);
            		}else{
            			$return['code'] = '10004';
			    		$return['data'] = '网络错误，请稍后重试!';
			    		ajaxReturn($return);
            		}
            	}else{
            		$return['code'] = '10001';
		    		$return['data'] = '修改失败,新密码与旧密码相同!';
		    		ajaxReturn($return);
            	}
            	
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
    	
    }

    

    
}