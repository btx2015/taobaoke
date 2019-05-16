<?php

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
        $result = json_decode($res,true,512,JSON_BIGINT_AS_STRING);
        if(isset($result['error_response']) || !isset($result['tbk_order_get_response'])){
            return [
                'result' => 'N',
                'msg' => $res
            ];
        }else{
            return $result;
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