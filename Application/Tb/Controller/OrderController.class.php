<?php
namespace Tb\Controller;
use Think\Controller;
Vendor('taobao.TopSdk');
class OrderController extends Controller {

    // const T_MANAGE_BANNER = 'tr_manage_banner';

    const T_ORDER = 'tr_commission_order';
    const T_MEMBER= 'tr_member';
	const T_ITEMS = 'tr_items';


	private $app_key ="25521171";
    private $secret_Key ="43967a2c75599e4027f7b5ff698b604b";

    public function index(){
    	$c = new \TopClient;
		$c->appkey = $this->app_key;
		$c->secretKey = $this->secret_Key;
		$req = new \TbkOrderGetRequest;
		$req->setFields("tb_trade_parent_id,tb_trade_id,num_iid,item_title,item_num,price,pay_price,seller_nick,seller_shop_title,commission,commission_rate,unid,create_time,earning_time,tk3rd_pub_id,tk3rd_site_id,tk3rd_adzone_id,relation_id,tb_trade_parent_id,tb_trade_id,num_iid,item_title,item_num,price,pay_price,seller_nick,seller_shop_title,commission,commission_rate,unid,create_time,earning_time,alipay_total_price,tk3rd_type,tk3rd_pub_id,tk_status,tk3rd_pub_id,tk3rd_site_id,tk3rd_adzone_id,special_id,click_time");
		$req->setStartTime("2019-05-06 13:22:50");
		$req->setOrderQueryType("create_time");
		// $req->setOrderScene("3");
		$resp = $c->execute($req);
		dump($resp);
    }

    public function indexggbanner(){
    	$model = M(self::T_MANAGE_BANNER);
    	$banner = $model->where(['state'=>'1'])->order('sort')->select();
        ajaxReturn($banner);
    }

    public function myorder(){
    	if ($_REQUEST['token']) {
            $txt = decodeing($_REQUEST['token']);
            if ($txt) {
                  
                $where = json_decode($txt);
                $model = M(self::T_MEMBER);
                $member = $model->where($where)->find();
                if($member){
                	$ordermodel = M(self::T_ORDER);
					$orders = $ordermodel->where(['user_id'=>$member['id']])->order('create_time')->select();
					$goodsmodel = M(self::T_ITEMS);
					foreach ($orders as $key => $value) {
						$goods = $goodsmodel->where(['itemid'=>$value['num_iid']])->field('itempic,activityid')->find();
						$orders[$key]['activityid'] = $goods['activityid'];
						$orders[$key]['itempic'] = $goods['itempic'];
						$orders[$key]['couponurl'] = 'taobao://uland.taobao.com/coupon/edetail?activityId='.$goods['activityid'].'&itemId='.$value['num_iid'].'&pid= mm_131227256_391700292_108697100321';
					}
					
					returnResult($orders);
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