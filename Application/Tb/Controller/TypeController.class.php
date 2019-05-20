<?php
namespace Tb\Controller;
use Think\Controller;
Vendor('taobao.TopSdk');
// Vendor('taobao.RSACrypto');
class TypeController extends Controller {

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
        if ($_GET['page']) {
            $s = $_GET['page']*10-9;
            $e = 10;
        }else{
            $s = 1;
            $e = 10;
        }
    	if (!is_null($_GET['type'])) {
    		$where['fqcat'] = $_GET['type'];
    	}
    	if ($_GET['sort'] == 'quant') {
    		$goodslist = $model->where($where)->limit($s,$e)->order('itemendprice')->select();
    	}elseif ($_GET['sort'] == 'quanb') {
    		$goodslist = $model->where($where)->limit($s,$e)->order('itemendprice desc')->select();
    	}elseif ($_GET['sort'] == 'salet') {
    		$goodslist = $model->where($where)->limit($s,$e)->order('itemsale')->select();
    	}elseif ($_GET['sort'] == 'saleb') {
    		$goodslist = $model->where($where)->limit($s,$e)->order('itemsale desc')->select();
    	}elseif ($_GET['sort'] == 'quantb') {
    		$goodslist = $model->where($where)->limit($s,$e)->order('couponmoney desc')->select();
    	}elseif ($_GET['sort'] == 'quanbt') {
    		$goodslist = $model->where($where)->limit($s,$e)->order('couponmoney')->select();
    	}elseif ($_GET['sort'] == 'zhpx') {
    		$goodslist = $model->where($where)->limit($s,$e)->select();
    	}elseif ($_GET['sort'] == 'tktb') {
    		$goodslist = $model->where($where)->limit($s,$e)->order('tkmoney desc')->select();
    	}else{
    		$goodslist = $model->where($where)->limit($s,$e)->select();
    	}

    	
    	returnResult($goodslist);
    }

    public function goodsdetail(){
        if ($_REQUEST['token']) {
            $txt = decodeing($_REQUEST['token']);
            if ($txt) {
                  
                $where = json_decode($txt);
                $model = M(self::T_MEMBER);
                $member = $model->where($where)->find();
                
            }
        }
        // returnResult($member);die;
        // $member = $this->member;
        $models = M(self::T_ITEMS);
        $goodsdetail = $models->where(['itemid'=>$_GET['itemid'],'state'=>'1'])->find();

        if (!$goodsdetail) {

            if($this->add_goods($_GET['itemid'])){
                $goodsdetail = $models->where(['itemid'=>$_GET['itemid'],'state'=>'1'])->find();
                $id = $goodsdetail['itemid'];
            }else{
                $urll = 'v2.api.haodanku.com/supersearch/apikey/jifenbao/keyword/'.$_GET['itemid'];
                $id = $_GET['itemid'];
                $resl = curlRequest($urll,'','GET');
                $resl= json_decode($resl,true);
                if ($resl['code']==1) {
                    $goodsdetail = $resl['data'][0];
                }else{
                    $res['code'] = 10008;
                    $res['data'] = '商品已失效或已下架';
                    ajaxReturn($res);
                }
                
            }
            
        }else{
            $id = $goodsdetail['itemid'];
        }
        // returnResult($resl['data'][0]);die;
        if (!empty($member)) {
            
            
            $token = alphaID($member['phone'],false,false,'tangrenjuhui');
    		
        	
        	// $url = 'taobao://uland.taobao.com/coupon/edetail?activityId='.$goodsdetail['activityid'].'&itemId='.$goodsdetail['itemid'].'&pid=mm_131227256_391700292_108697100321&relationId=';
        	// dump($member);die;
            if ($member['special_id']=='0'||$member['special_id']=='') {
                // echo "string";die;
                $goodsdetail['couponurltype'] = 'shou';
                $url = 'https://oauth.taobao.com/authorize?response_type=code&client_id=25521171&redirect_uri=http://jx.tangrenjuhui.com/index.php/Tb/Index/shouquan.html&state='.$token.'t1r2j3h'.$goodsdetail['itemid'].'&view=wap';
            }else{
                $goodsdetail['couponurltype'] = 'quan';
                $url = 'taobao://uland.taobao.com/coupon/edetail?activityId='.$goodsdetail['activityid'].'&itemId='.$goodsdetail['itemid'].'&pid=mm_131227256_391700292_108697100321';
            }
        }else{
            $goodsdetail['couponurltype'] = 'loginx';
            $url = '_www/html/loginx.html';
        }
    	$goodsdetail['couponurl'] = $url;
    	
        // returnResult($goodsdetail);die;
        $urls = 'https://acs.m.taobao.com/h5/mtop.taobao.detail.getdetail/6.0/?data={%22itemNumId%22%3A%22'.$id.'%22}';
        $res = curlRequest($urls,'','GET');
        $res = json_decode($res,true);
        if(!$res['data']['item']['images']){
            $res['code'] = 10008;
            $res['data'] = '商品已失效或已下架';
            ajaxReturn($res);
        }
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
    public function hhyx(){
        $model = M(self::T_ITEMS);
        $where['state'] = '1';
        if ($_GET['page']) {
            $s = $_GET['page']*10-9;
            $e = 10;
        }else{
            $s = 1;
            $e = 10;
        }
        $goodslist = $model->where($where)->order('general_index desc')->limit($s,$e)->select();
        returnResult($goodslist);
    }

    public function zytj(){
        $model = M(self::T_ITEMS);
        $where['state'] = '1';
        if ($_GET['page']) {
            $s = $_GET['page']*10-9;
            $e = 10;
        }else{
            $s = 1;
            $e = 10;
        }
        $goodslist = $model->where($where)->limit($s,$e)->order('general_index desc')->select();
        returnResult($goodslist);
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
		$request_data['itemid'] = '593511096524';
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
   
    private function add_goods($itemid){
        $url = 'http://v2.api.haodanku.com/item_detail/apikey/jifenbao/itemid/'.$itemid;
        $res = curlRequest($url,'','GET');
        $res = json_decode($res,true);
        // dump($res);die;
        if($res['code']==1){
            $this->add_data($res['data']);
        }else{
            return false;
        }
    }

    private function add_data($data){
        $model = M(self::T_ITEMS);
        $time = time();
        // array_walk($data,function(&$v)use($time){
            $data['created_at'] = $time;
        // });
        $insertId = $model->add($data);
        if(!$insertId){
            Log::record('商品数据添加失败，原因：'.$insertId,'ERR');
            exit('create error');
        }
        return true;
    }

    public function test(){
        //接口地址
        $host = 'https://openapi.dataoke.com/api/goods/get-goods-details';
        $appKey = '5cd532dd0ec1f';//应用的key
        $appSecret = '1fd7da4f50cb79ef106cdf1a6281df1d';//应用的Secret
        //默认必传参数
        $data = [
            'appKey' => $appKey,
            'version' => '1',
            'goodsId' =>'577638197493'
        ];
        //加密的参数
        $data['sign'] = makeSign($data,$appSecret);
        //拼接请求地址
        $url = $host .'?'. http_build_query($data);
        var_dump($url);
        //执行请求获取数据
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_TIMEOUT,10);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        var_dump($output);
    }
}