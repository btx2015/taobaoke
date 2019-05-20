<?php
namespace Tb\Controller;
use Tb\Controller\CommonController;

class UserController extends CommonController {

	const T_MEMBER = 'tr_member';
	const T_MY_BANNER = 'tr_my_banner';

    public function nickname(){

    	$member = $this->member;

    	$data['nickname'] = $member['name'];

		returnResult($data);

    }


    public function nicknameedit(){

    	$member = $this->member;

    	$model = M(self::T_MEMBER);
        $edit = $model->where($member)->save(['name'=>$_POST['nickname']]);
        if ($edit) {
        	$return['code'] = '10000';
    		$return['data'] = '修改成功';
    		ajaxReturn($return);
        }else{
        	$return['code'] = '10001';
    		$return['data'] = '修改失败';
    		ajaxReturn($return);
        }

    }


    public function info(){
    	$member = $this->member;

    	$model = M(self::T_MEMBER);

        $members = $model->where(['id'=>$member['id']])->field(
        	'id,name,member_points,phone,level,invitecode,available_fund,avatar'
        )->find();
        // $members['invitecode'] = alphaID($members['phone'],false,false,'tangrenjuhui');
        // dump($member);die;
        if ($members) {
        	$return['code'] = '10000';
    		$return['data'] = $members;
    		ajaxReturn($return);
        }else{
        	$return['code'] = '10001';
    		$return['data'] = '网络错误';
    		ajaxReturn($return);
        }
    }

    public function bindwechat(){
    	// ajaxReturn($_POST);die;
    	$member = $this->member;

    	$model = M(self::T_MEMBER);
        $edit = $model->where(['id'=>$member['id']])->save([
        	
        	'wx_nickname'=>base64_encode($_POST['nickname']),
        	'avatar'=>$_POST['avatar'],
        	'openid'=>$_POST['openid'],
        	'unionid'=>$_POST['unionid'],
        ]);
        if ($edit) {
        	$return['code'] = '10000';
    		$return['data'] = '绑定成功';
    		ajaxReturn($return);
        }else{
        	$return['code'] = '10001';
    		$return['data'] = '绑定失败';
    		ajaxReturn($return);
        }
    }

    public function banner(){
    	$model = M(self::T_MY_BANNER);
    	$banner = $model->where(['state'=>'1'])->order('sort')->select();
        ajaxReturn($banner);
    }


    public function fans(){
    	$member = $this->member;

    	$model = M(self::T_MEMBER);

        $data['info'] = $model->where(['id'=>$member['id']])->field(
        	'id,name,referee_id,phone,level,created_at,avatar'
        )->find();

        $data['referee'] = $model->where(['id'=>$data['info']['referee_id']])->field(
        	'id,name,referee_id,phone,level,created_at,avatar'
        )->find();

        $data['directfans'] = $model->where(['referee_id'=>$member['id']])->count();

        $data['directfanslist'] = $model->where(['referee_id'=>$member['id']])->order('created_at')->limit(10)->select();
        // $data['allfanslist'] = $data['directfanslist'];
        // foreach ($$data['allfanslist'] as $key => $value) {
        // 	$fans = $model->where(['referee_id'=>$value['id']])->field(
        // 		'id,name,referee_id,phone,level,created_at,avatar'
        // 	)->select();
        // 	$data['allfanslist'] = array_merge($data['allfanslist'],$fans);
        // }

        ajaxReturn($data);
    }

    public function fanspage(){
    	$member = $this->member;

    	$model = M(self::T_MEMBER);
    	if ($_GET['page']) {
            $s = $_GET['page']*10+1;
            $e = 10;
        }else{
            $s = 11;
            $e = 10;
        }

        $data['directfanslist'] = $model->where(['referee_id'=>$member['id']])->order('created_at')->limit($s,$e)->select();
        // $data['allfanslist'] = $data['directfanslist'];
        // foreach ($$data['allfanslist'] as $key => $value) {
        // 	$fans = $model->where(['referee_id'=>$value['id']])->field(
        // 		'id,name,referee_id,phone,level,created_at,avatar'
        // 	)->select();
        // 	$data['allfanslist'] = array_merge($data['allfanslist'],$fans);
        // }

        ajaxReturn($data);
    }
}