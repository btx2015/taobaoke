<?php
namespace Tb\Controller;
use Think\Controller;
Vendor('taobao.TopSdk');
class SearchController extends Controller {

	// const T_MEMBER= 'tr_member';
    private $app_key ="25521171";
    private $secret_Key ="43967a2c75599e4027f7b5ff698b604b";

    public function search(){
        $key = urlencode(urlencode($_POST['key']));
        if ($_POST['sort'] == 'quant') {
            $sort = '4';
        }elseif ($_POST['sort'] == 'quanb') {
            $sort = '5';
        }elseif ($_POST['sort'] == 'salet') {
            $sort = '3';
        }elseif ($_POST['sort'] == 'saleb') {
            $sort = '2';
        }elseif ($_POST['sort'] == 'zuixin') {
            $sort = '1';
        }elseif ($_POST['sort'] == 'zhpx') {
            $sort = '0';
        }elseif ($_POST['sort'] == 'tktb') {
            $sort = '6';
        }else{
            $sort = '0';
        }
        if ($_POST['tb_p']) {
            $tb_p = $_POST['tb_p'];
        }else{
            $tb_p = '1';
        }
        if ($_POST['min_id']) {
            $min_id = $_POST['min_id'];
        }else{
            $min_id = '1';
        }
        
        // 0.综合，1.最新，2.销量（高到低），3.销量（低到高），4.价格(低到高)，5.价格（高到低），6.佣金比例（高到低）
        // $url = 'http://v2.api.haodanku.com/get_keyword_items/apikey/jifenbao/keyword/'.$key.'/back/10/sort/'.$sort.'/cid/0';
    	$url = 'http://v2.api.haodanku.com/supersearch/apikey/jifenbao/keyword/'.$key.'/back/10/min_id/'.$min_id.'/tb_p/'.$tb_p.'/sort/'.$sort.'/is_tmall/0/is_coupon/1/limitrate/0';
        $res = curlRequest($url,'','GET');
        $res = json_decode($res,true);
        // $res['sort'] = $sort;
        ajaxReturn($res);
    	
    }

    public function typelist(){

        
        if ($_POST['sort'] == 'quant') {
            $sort = '1';
        }elseif ($_POST['sort'] == 'quanb') {
            $sort = '2';
        }elseif ($_POST['sort'] == 'salet') {
            $sort = '7';
        }elseif ($_POST['sort'] == 'saleb') {
            $sort = '4';
        }elseif ($_POST['sort'] == 'zuixin') {
            $sort = '14';
        }elseif ($_POST['sort'] == 'zhpx') {
            $sort = '0';
        }elseif ($_POST['sort'] == 'tktb') {
            $sort = '5';
        }else{
            $sort = '0';
        }
        if ($_POST['min_id']) {
            $min_id = $_POST['min_id'];
        }else{
            $min_id = '1';
        }
        $type = $_POST['type'];
        if ($_POST['cid']) {
            $cid = $_POST['cid'];
        }else{
            $cid = '0';
        }
        // 0.综合，1.最新，2.销量（高到低），3.销量（低到高），4.价格(低到高)，5.价格（高到低），6.佣金比例（高到低）
        // $url = 'http://v2.api.haodanku.com/get_keyword_items/apikey/jifenbao/keyword/'.$key.'/back/10/sort/'.$sort.'/cid/0';
        $url = 'http://v2.api.haodanku.com/column/apikey/jifenbao/type/'.$type.'/cid/'.$cid.'/back/10/min_id/'.$min_id.'/sort/'.$sort;
        $res = curlRequest($url,'','GET');
        $res = json_decode($res,true);
        // $res['sort'] = $sort;
        ajaxReturn($res);
        
    }

    public function hotkey()
    {
        $url = 'http://v2.api.haodanku.com/hot_key/apikey/jifenbao';
        $res = curlRequest($url,'','GET');
        $res = json_decode($res,true);
        ajaxReturn($res);
    }

    
}