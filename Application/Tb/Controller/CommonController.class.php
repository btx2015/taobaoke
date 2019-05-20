<?php

namespace Tb\Controller;

use Think\Controller;


class CommonController extends Controller
{
    const T_MEMBER= 'tr_member';

    public $member;

    public $token;

    public function _initialize()
    {   
        $txt = decodeing($_REQUEST['token']);
        if ($txt) {
              
            $where = json_decode($txt);
            $model = M(self::T_MEMBER);
            $member = $model->where($where)->find();
            // dump($_REQUEST['token']);die;
            if (!$member) {
                $return['code'] = 400;
                $return['data'] = '非法请求';
                ajaxReturn($return);die;
            }else{
                $this->member = $member;
                $this->token = $_REQUEST['token'];
            }
        }else{
            $return['code'] = 400;
            $return['data'] = '非法请求';
            ajaxReturn($return);die;
            
        }
    }

    
}