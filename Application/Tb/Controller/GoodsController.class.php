<?php
namespace Tb\Controller;
use Tb\Controller\CommonController;
Vendor('taobao.TopSdk');
// Vendor('taobao.RSACrypto');
class GoodsController extends CommonController {

	private $app_key ="25521171";
    private $secret_Key ="43967a2c75599e4027f7b5ff698b604b";

	const T_CATEGORY = 'tr_category';
	const T_ITEMS = 'tr_items';
	const T_MEMBER= 'tr_member';

    


	public function type()
	{	

		$model = M(self::T_CATEGORY);
		$t = $model->where(['state'=>'1'])->order('sort')->select();
		$data = $this->cateFormat($t);
		$data = array_values($data);
		returnResult($data);

	}
	private function cateFormat($cate,$pid = 0,$level = 0)
	{
		$data = [];
	    foreach($cate as $k => $v){
	        if($v['pid'] == $pid){
            	$data[$v['id']] = [
	                'id'   => $v['id'],
	                'name' => $v['name'],
	            ];
	            unset($cate[$k]);
	            $children = $this->cateFormat($cate,$v['id'],$level+1);
	            if($children){
	            	$data[$v['id']]['children'] = $children;
	            	$data[$v['id']]['children'] = array_values($data[$v['id']]['children']);
        		}
            }

	    }
	    return $data;
	}
    public function goodslist(){
    	$model = M(self::T_ITEMS);
    	$where['state'] = '1';
    	if (!is_null($_GET['type'])) {
    		$where['fqcat'] = $_GET['type'];
    	}
    	if ($_GET['sort'] == 'quant') {
    		$goodslist = $model->where($where)->limit(20)->order('itemendprice')->select();
    	}elseif ($_GET['sort'] == 'quanb') {
    		$goodslist = $model->where($where)->limit(20)->order('itemendprice desc')->select();
    	}elseif ($_GET['sort'] == 'salet') {
    		$goodslist = $model->where($where)->limit(20)->order('itemsale')->select();
    	}elseif ($_GET['sort'] == 'saleb') {
    		$goodslist = $model->where($where)->limit(20)->order('itemsale desc')->select();
    	}elseif ($_GET['sort'] == 'quantb') {
    		$goodslist = $model->where($where)->limit(20)->order('couponmoney desc')->select();
    	}elseif ($_GET['sort'] == 'quanbt') {
    		$goodslist = $model->where($where)->limit(20)->order('couponmoney')->select();
    	}elseif ($_GET['sort'] == 'zhpx') {
    		$goodslist = $model->where($where)->limit(20)->select();
    	}elseif ($_GET['sort'] == 'tktb') {
    		$goodslist = $model->where($where)->limit(20)->order('tkmoney desc')->select();
    	}else{
    		$goodslist = $model->where($where)->limit(20)->select();
    	}

    	
    	returnResult($goodslist);
    }

    public function goodsdetail(){
        $member = $this->member;
        $token = alphaID($member['phone'],false,false,'tangrenjuhui');
		$model = M(self::T_ITEMS);
    	$goodsdetail = $model->where(['itemid'=>$_GET['itemid'],'state'=>'1'])->find();
    	// $url = 'taobao://uland.taobao.com/coupon/edetail?activityId='.$goodsdetail['activityid'].'&itemId='.$goodsdetail['itemid'].'&pid=mm_131227256_391700292_108697100321&relationId=';
    	// dump($member);die;
        if ($member['special_id']=='0') {
            // echo "string";die;
            $goodsdetail['couponurltype'] = 'shou';
            $url = 'https://oauth.taobao.com/authorize?response_type=code&client_id=25521171&redirect_uri=http://jx.tangrenjuhui.com/index.php/Tb/Index/shouquan.html&state='.$token.'t1r2j3h'.$goodsdetail['itemid'].'&view=wap';
        }else{
            $goodsdetail['couponurltype'] = 'quan';
            $url = 'taobao://uland.taobao.com/coupon/edetail?activityId='.$goodsdetail['activityid'].'&itemId='.$goodsdetail['itemid'].'&pid=mm_131227256_391700292_108697100321';
        }
    	$goodsdetail['couponurl'] = $url;
    	$id = $goodsdetail['itemid'];
        $urls = 'https://acs.m.taobao.com/h5/mtop.taobao.detail.getdetail/6.0/?data={%22itemNumId%22%3A%22'.$id.'%22}';
        $res = curlRequest($urls,'','GET');
        $res = json_decode($res,true);
        // $data['title'] = $res['data']['item']['title'];
        // $data['title'] = $res['data']['item']['title'];
        $data['images'] = $res['data']['item']['images'];
        $data['shopIcon'] = $res['data']['seller']['shopIcon'];
        $data['shopName'] = $res['data']['seller']['shopName'];
        $data['evaluates'] = $res['data']['seller']['evaluates'];
        $value = json_decode($res['data']['apiStack'][0]['value'],true);
        $data['sellCount'] = $value['item']['sellCount'];
        $data['delivery'] = $value['delivery'];
        // $data['moduleDescUrl'] = $res['data']['item']['moduleDescUrl'];

        $moduleDescUrl = curlRequest('https:'.$res['data']['item']['moduleDescUrl'],'','GET');
        $moduleDescUrl = json_decode($moduleDescUrl,true);
        $data['moduleDescUrl'] = $moduleDescUrl['data']['children'];
        $data['tmallDescUrl'] = $res['data']['item']['taobaoDescUrl'];
    	$goodsdetail['data'] = $data;
    	returnResult($goodsdetail);
    }

    public function zhlj(){
    	$id = $_GET['id'];
    	$url = 'https://acs.m.taobao.com/h5/mtop.taobao.detail.getdetail/6.0/?data={%22itemNumId%22%3A%22'.$id.'%22}';
    	$res = curlRequest($url,'','GET');
    	$res = json_decode($res,true);
    	// $data['title'] = $res['data']['item']['title'];
    	// $data['title'] = $res['data']['item']['title'];
    	$data['images'] = $res['data']['item']['images'];
    	$data['shopIcon'] = $res['data']['seller']['shopIcon'];
    	$data['shopName'] = $res['data']['seller']['shopName'];
    	$data['evaluates'] = $res['data']['seller']['evaluates'];
    	$value = json_decode($res['data']['apiStack'][0]['value'],true);
    	$data['sellCount'] = $value['item']['sellCount'];
    	$data['delivery'] = $value['delivery'];
    	// $data['moduleDescUrl'] = $res['data']['item']['moduleDescUrl'];

    	$moduleDescUrl = curlRequest('https:'.$res['data']['item']['moduleDescUrl'],'','GET');
    	$moduleDescUrl = json_decode($moduleDescUrl,true);
    	$data['moduleDescUrl'] = $moduleDescUrl['data']['children'];
    	$data['tmallDescUrl'] = $res['data']['item']['taobaoDescUrl'];
    	returnResult($data);
    }

    public function zhljs(){
    	$request_url = 'http://v2.api.haodanku.com/ratesurl';
		$request_data['apikey'] = 'jifenbao';
		$request_data['itemid'] = '529048526383';
		$request_data['pid'] = 'mm_131227256_391700292_108697500236';
		// $request_data['activityid'] = '7d6e6619ff754e1e94ea140e2a82240f';
		$request_data['tb_name'] = '唐人聚惠';
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$request_url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT,10);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$request_data);
		$res = curl_exec($ch);
		curl_close($ch);
		var_dump($res); 
    }

    public function optimus(){
		$c = new \TopClient;
		$c->appkey = $this->app_key;
		$c->secretKey = $this->secret_Key;
		$req = new \TbkDgOptimusMaterialRequest;
		$req->setPageSize("20");
		$req->setAdzoneId("107518400214");
		$req->setPageNo("1");
		$req->setMaterialId("9660");
		$req->setDeviceValue("fgasdaaaaaaaasssasdsasdsasdasdsa");
		$req->setDeviceEncrypt("MD5");
		$req->setDeviceType("IMEI");
		$req->setContentId("588612575366");
		$req->setContentSource("xxx");
		$req->setItemId("588612575366");
		$resp = $c->execute($req);
		dump($resp);
    }
   

    
}