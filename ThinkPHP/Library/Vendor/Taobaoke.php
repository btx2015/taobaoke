<?php
/**
 * Created by PhpStorm.
 * User: leasin
 * Date: 2019/5/13
 * Time: 16:32
 */

class Taobaoke
{

    public $appKey = '';

    public $appSecret = '';

    const API_URL = 'gw.api.taobao.com/router/rest';

    public function __construct($appKey,$appSecret)
    {
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
    }

    /**
     * @param array $params
     * @return array
     * 接口文档
     * https://developer.alibaba.com/docs/api.htm?spm=a219a.7395905.0.0.29e675fex8tgK4&apiId=24527
     */
    public function request(array $params){
        $params = array_merge([
            'app_key' => $this->appKey,
            'sign_method' => 'md5',
            'timestamp' => date('Y-m-d H:i:s',time()),
            'format' => 'json',
            'v' => '2.0',
        ],$params);
        $params['sign'] = $this->sign($params);

        $res = curlRequest(self::API_URL,$params);
        $result = json_decode($res,true);
        if(isset($result['error_response']) || !isset($result['tbk_order_get_response'])){
            return [
                'result' => 'N',
                'msg' => $res
            ];
        }else{
            return [
                'result' => 'Y',
                'data' => $result['tbk_order_get_response']['results']
            ];
        }
    }

    private function sign($params){
        ksort($params);
        $string = '';
        foreach($params as $k => $v){
            $string .= $k.$v;
        }
        $sign = md5($this->appSecret.$string.$this->appSecret);

        return strtoupper($sign);
    }
}