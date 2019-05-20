<?php 
/**
* Translates a number to a short alhanumeric version
*
* Translated any number up to 9007199254740992
* to a shorter version in letters e.g.:
* 9007199254740989 --> PpQXn7COf
*
* specifiying the second argument true, it will
* translate back e.g.:
* PpQXn7COf --> 9007199254740989
*
* this function is based on any2dec && dec2any by
* fragmer[at]mail[dot]ru
* see: http://nl3.php.net/manual/en/function.base-convert.php#52450
*
* If you want the alphaID to be at least 3 letter long, use the
* $pad_up = 3 argument
*
* In most cases this is better than totally random ID generators
* because this can easily avoid duplicate ID's.
* For example if you correlate the alpha ID to an auto incrementing ID
* in your database, you're done.
*
* The reverse is done because it makes it slightly more cryptic,
* but it also makes it easier to spread lots of IDs in different
* directories on your filesystem. Example:
* $part1 = substr($alpha_id,0,1);
* $part2 = substr($alpha_id,1,1);
* $part3 = substr($alpha_id,2,strlen($alpha_id));
* $destindir = "/".$part1."/".$part2."/".$part3;
* // by reversing, directories are more evenly spread out. The
* // first 26 directories already occupy 26 main levels
*
* more info on limitation:
* - http://blade.nagaokaut.ac.jp/cgi-bin/scat.rb/ruby/ruby-talk/165372
*
* if you really need this for bigger numbers you probably have to look
* at things like: http://theserverpages.com/php/manual/en/ref.bc.php
* or: http://theserverpages.com/php/manual/en/ref.gmp.php
* but I haven't really dugg into this. If you have more info on those
* matters feel free to leave a comment.
*
* @author  Kevin van Zonneveld <kevin@vanzonneveld.net>
* @author  Simon Franz
* @author  Deadfish
* @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
* @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
* @version   SVN: Release: $Id: alphaID.inc.php 344 2009-06-10 17:43:59Z kevin $
* @link    http://kevin.vanzonneveld.net/
*
* @param mixed   $in    String or long input to translate
* @param boolean $to_num  Reverses translation when true
* @param mixed   $pad_up  Number or boolean padds the result up to a specified length
* @param string  $passKey Supplying a password makes it harder to calculate the original ID
*
* @return mixed string or long
*/
function alphaID($in, $to_num = false, $pad_up = false, $passKey = null)
{
    $index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    if ($passKey !== null) {
// Although this function's purpose is to just make the
// ID short - and not so much secure,
// with this patch by Simon Franz (http://blog.snaky.org/)
// you can optionally supply a password to make it harder
// to calculate the corresponding numeric ID

        for ($n = 0; $n<strlen($index); $n++) {
            $i[] = substr( $index,$n ,1);
        }

        $passhash = hash('sha256',$passKey);
        $passhash = (strlen($passhash) < strlen($index))
        ? hash('sha512',$passKey)
        : $passhash;

        for ($n=0; $n < strlen($index); $n++) {
            $p[] =  substr($passhash, $n ,1);
        }

        array_multisort($p,  SORT_DESC, $i);
        $index = implode($i);
    }

    $base  = strlen($index);

    if ($to_num) {
// Digital number  <<--  alphabet letter code
        $in  = strrev($in);
        $out = 0;
        $len = strlen($in) - 1;
        for ($t = 0; $t <= $len; $t++) {
            $bcpow = bcpow($base, $len - $t);
            $out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
        }

        if (is_numeric($pad_up)) {
            $pad_up--;
            if ($pad_up > 0) {
                $out -= pow($base, $pad_up);
            }
        }
        $out = sprintf('%F', $out);
        $out = substr($out, 0, strpos($out, '.'));
    } else {
// Digital number  -->>  alphabet letter code
        if (is_numeric($pad_up)) {
            $pad_up--;
            if ($pad_up > 0) {
                $in += pow($base, $pad_up);
            }
        }

        $out = "";
        for ($t = floor(log($in, $base)); $t >= 0; $t--) {
            $bcp = bcpow($base, $t);
            $a   = floor($in / $bcp) % $base;
            $out = $out . substr($index, $a, 1);
            $in  = $in - ($a * $bcp);
        }
$out = strrev($out); // reverse
}

return $out;
}
/** 
* 公钥加密 
* 
* @param string 明文  
* @return string 密文（base64编码） 
*/  
function encodeing($sourcestr)  
{
    $key_content = file_get_contents('/www/wwwroot/tr.wei587.top/rsa_public_key.pem');  
    $pubkeyid    = openssl_get_publickey($key_content);  

    if (openssl_public_encrypt($sourcestr, $crypttext, $pubkeyid))  
    {
        return base64_encode("".$crypttext);  
    }
}

/** 
* 私钥解密 
* 
* @param string 密文（二进制格式且base64编码）
* @param string 密文是否来源于JS的RSA加密 
* @return string 明文 
*/  
function decodeing($crypttext)  
{
    $key_content = file_get_contents('/www/wwwroot/tr.wei587.top/rsa_private_key.pem');  
    $prikeyid    = openssl_get_privatekey($key_content);  
    $crypttext   = base64_decode($crypttext);

    if (openssl_private_decrypt($crypttext, $sourcestr, $prikeyid, OPENSSL_PKCS1_PADDING))  
    {
        return "".$sourcestr;  
    }
    return ;  
}

function sendsms($phone){
    $smscode = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
    $content="您的动态短信验证码为:".$smscode.".该验证码3分钟内有效,请勿泄露给他人.如不是本人操作,请忽略.";//要发送的短信内容
    $phone = $phone;//要发送短信的手机号码
    $url="http://api.feige.ee/SmsService/Send";
    $data['Account'] = '18045261327';
    $data['Pwd']     = '371f81f73e23cd8d179938e05';
    $data['Content'] = $content;
    $data['Mobile']  = $phone;
    $data['SignId']  = '127728';
    $res=post($url,$data);
    if ($res['Code']!=0) {
        $ress['code'] = '10001';
        return false;die;
    }else{
        $ress['code'] = '10000';
        $ress['smscode'] = $smscode;
        return $ress;die;
    }
}
function post($url, $data, $proxy = null, $timeout = 20) {
    $curl = curl_init();  
    curl_setopt($curl, CURLOPT_URL, $url);    
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); //在HTTP请求中包含一个"User-Agent: "头的字符串。        
    curl_setopt($curl, CURLOPT_HEADER, 0); //启用时会将头文件的信息作为数据流输出。   
    curl_setopt($curl, CURLOPT_POST, true); //发送一个常规的Post请求  
    curl_setopt($curl,  CURLOPT_POSTFIELDS, $data);//Post提交的数据包  
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); //启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量。     
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //文件流形式         
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); //设置cURL允许执行的最长秒数。   
    $content = curl_exec($curl);  
    curl_close($curl);  
    unset($curl);
    return $content;  
}
    /**参数加密

     * @param $data

     * @param $appSecret

     * @return string

     */

    function makeSign($data, $appSecret)
    {
        ksort($data);
        $str = '';
        foreach ($data as $k => $v) {

            $str .= '&' . $k . '=' . $v;
        }
        $str = trim($str, '&');
        $sign = strtoupper(md5($str . '&key=' . $appSecret));
        return $sign;
    }



?>