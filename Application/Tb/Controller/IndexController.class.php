<?php
namespace Tb\Controller;
use Think\Controller;
Vendor('taobao.TopSdk');
class IndexController extends Controller {
	private $app_key ="25521171";
    private $secret_Key ="43967a2c75599e4027f7b5ff698b604b";
    const T_MEMBER= 'tr_member';
    public function shouquan(){
    	$code = $_GET['code'];
    	$data = $this->GetOpenidFromMp($code);
    	// dump($data);die;
		$ss = $this->beian($data['access_token']);
		$data = explode('t1r2j3h',$_GET['state']);
		// dump($data);die;
		$txt = alphaID($data[0],true,false,'tangrenjuhui');
		$where['phone'] = $txt;
		$model = M(self::T_MEMBER);
        $member = $model->where($where)->find();
		if ($member) {
			$model->where($where)->save(['special_id'=>$ss['data']['special_id']]);
			$this->display();
			// echo $ss['data']['special_id'];
		}else{

		}
		// dump($data);

    }

    public function getid($access_token)
    {
    	
		$c = new \TopClient;
		$c->appkey = $this->app_key;
		$c->secretKey = $this->secret_Key;
		// $sessionKey="6101a13*************a51662159";
		$req = new \TbkScInvitecodeGetRequest;
		$req->setRelationId("551398151");
		$req->setRelationApp("common");
		$req->setCodeType("3");
		$resp = $c->execute($req, $access_token);
		
		$jsonStr = json_encode($resp);                  //先将SimpleXMLElement Object转字符串
        $jsonArray = json_decode($jsonStr, true);       //再转json对象
		// $data = json_decode($resp,true);
		dump($jsonArray);
		// $ss = $this->beian($access_token,$jsonArray['data']['inviter_code']);
		// dump($jsonArray);die;
    }

    public function beian($access_token)
    {
		$c = new \TopClient;
		$c->appkey = $this->app_key;
		$c->secretKey = $this->secret_Key;
		$req = new \TbkScPublisherInfoSaveRequest;
		$req->setRelationFrom("唐人聚惠");
		// $req->setOfflineScene("1");
		$req->setOnlineScene("3");
		$req->setInviterCode("HN7UL2");
		$req->setInfoType("1");
		$req->setNote("唐人聚惠");
		$resp = $c->execute($req, $access_token);
		$jsonStr = json_encode($resp);
        $jsonArray = json_decode($jsonStr, true); 
		return $jsonArray;
    }
    public function huiyuanid($access_token)
    {
    	$c = new \TopClient;
		$c->appkey = $this->app_key;
		$c->secretKey = $this->secret_Key;
		$req = new \TbkScPublisherInfoSaveRequest;
		$req->setRelationFrom("唐人聚惠");
		// $req->setOfflineScene("1");
		$req->setOnlineScene("3");
		$req->setInviterCode("H7MK2X");
		$req->setInfoType("1");
		$req->setNote("唐人聚惠");
		$resp = $c->execute($req, $access_token);
		dump($resp);die;
    }
    private function __CreateOauthUrlForOpenid($code)
	{
		$urlObj["client_id"] = "25521171";
		$urlObj["client_secret"] = "43967a2c75599e4027f7b5ff698b604b";
		$urlObj["redirect_uri"] = "http://jx.tangrenjuhui.com/index.php/Tb/Index/shouquan.html";
		$urlObj["code"] = $code;
		$bizString = $this->ToUrlParams($urlObj);
		return "https://oauth.taobao.com/token?grant_type=authorization_code&response_type=code&".$bizString;
	}

	public function GetOpenidFromMp($code)
	{
		$url = $this->__CreateOauthUrlForOpenid($code);
		// dump($url);die;
		//初始化curl
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, 1);
		
		//运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
		//取出openid
		$data = json_decode($res,true);
		
		return $data;
	}
	private function ToUrlParams($urlObj)
	{
		$buff = "";
		foreach ($urlObj as $k => $v)
		{
			if($k != "sign"){
				$buff .= $k . "=" . $v . "&";
			}
		}
		
		$buff = trim($buff, "&");
		return $buff;
	}

	public function test(){
		$this->display();
	}

	public function index(){
		$this->display();
	}
}