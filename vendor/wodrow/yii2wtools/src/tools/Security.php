<?php
namespace wodrow\yii2wtools\tools;


class Security
{
    /**
     * tp系统加密方法
     * @param string $data 要加密的字符串
     * @param string $key  加密密钥
     * @param int $expire  过期时间 单位 秒
     * @return string
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public static function think_encrypt($data, $key = '', $expire = 0) {
        $key  = md5(empty($key) ? 'wodrow' : $key);
        $data = base64_encode($data);
        $x    = 0;
        $len  = strlen($data);
        $l    = strlen($key);
        $char = '';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) $x = 0;
            $char .= substr($key, $x, 1);
            $x++;
        }
        $str = sprintf('%010d', $expire ? $expire + time():0);
        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1)))%256);
        }
        return str_replace(array('+','/','='),array('-','_',''),base64_encode($str));
    }
    /**
     * tp系统解密方法
     * @param  string $data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
     * @param  string $key  加密密钥
     * @return string
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public static function think_decrypt($data, $key = ''){
        $key    = md5(empty($key) ? 'wodrow' : $key);
        $data   = str_replace(array('-','_'),array('+','/'),$data);
        $mod4   = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        $data   = base64_decode($data);
        $expire = substr($data,0,10);
        $data   = substr($data,10);
        if($expire > 0 && $expire < time()) {
            return '';
        }
        $x      = 0;
        $len    = strlen($data);
        $l      = strlen($key);
        $char   = $str = '';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) $x = 0;
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($data, $i, 1))<ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            }else{
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return base64_decode($str);
    }
    /**
     * 数据签名认证
     * @param  array  $data 被认证的数据
     * @return string       签名
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public static function data_auth_sign($data) {
        //数据类型检测
        if(!is_array($data)){
            $data = (array)$data;
        }
        ksort($data); //排序
        $code = http_build_query($data); //url编码并生成query字符串
        $sign = sha1($code); //生成签名
        return $sign;
    }

    /**
     * 修改权限
     * @param $path
     * @param $filemode
     * @return bool
     */
    public static function chmodr($path, $filemode)
    {
        if (!is_dir($path))
            return chmod($path, $filemode);
        $dh = opendir($path);
        while (($file = readdir($dh)) !== false) {
            if ($file != '.' && $file != '..') {
                $fullpath = $path . '/' . $file;
                if (is_link($fullpath))
                    return FALSE;
                elseif (!is_dir($fullpath) && !chmod($fullpath, $filemode))
                    return FALSE;
                elseif (!self::chmodr($fullpath, $filemode))
                    return FALSE;
            }
        }
        closedir($dh);
        if (chmod($path, $filemode))
            return TRUE;
        else
            return FALSE;
    }

    /**
     * RSA签名
     * @param $data 待签名数据(按照文档说明拼成的字符串)
     * @param $priKey商户私钥
     * return 签名结果
     */
    public static function rsaSign($data, $priKey)
    {
        $res = openssl_get_privatekey($priKey);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * RSA验签
     * @param mixed $data 待签名数据(如果是xml返回则数据为<plain>标签的值,包含<plain>标签，如果为form(key-value，一般指异步返回类的)返回,则需要按照文档中进行key的顺序进行，value拼接)
     * @param $ali_public_key_path 富友的公钥文件路径
     * @param $sign 要校对的的签名结果
     * return 验证结果
     */
    public static function rsaVerify($data, $pubKey, $sign, $ali_public_key_path = null)
    {
        if ($ali_public_key_path) $pubKey = file_get_contents($ali_public_key_path);
        $res = openssl_get_publickey($pubKey);
        $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        openssl_free_key($res);
        return $result;
    }

    /**
     * RSA加密
     * @param $content
     * @param $public_key
     * return 加密后内容
     */
    public static function rsaEncrypt($content, $public_key){
        $pubKey = $public_key;
        $res = openssl_get_publickey($pubKey);
        //把需要加密的内容，按128位拆开解密
        $result  = '';
        for($i = 0; $i < strlen($content)/128; $i++  ) {
            $data = substr($content, $i * 128, 128);
            openssl_public_encrypt ($data, $encrypt, $res);
            $result .= $encrypt;
        }
        $result = base64_encode($result);
        openssl_free_key($res);
        return $result;
    }

    /**
     * RSA解密
     * @param $content 需要解密的内容，密文
     * @param $private_key 商户私钥文件路径
     * return 解密后内容，明文
     */
    public static function rsaDecrypt($content, $private_key) {
        $priKey = $private_key;
        $res = openssl_get_privatekey($priKey);
        //用base64将内容还原成二进制
        $content = base64_decode($content);
        //把需要解密的内容，按128位拆开解密
        $result  = '';
        for($i = 0; $i < strlen($content)/128; $i++  ) {
            $data = substr($content, $i * 128, 128);
            openssl_private_decrypt($data, $decrypt, $res);
            $result .= $decrypt;
        }
        openssl_free_key($res);
        return $result;
    }

    /**
     * 验签  验证签名  基于sha1withRSA
     * @param $data 签名前的原字符串
     * @param $signature 签名串
     * @return bool
     * @link www.zh30.com
     */
    public static function verify($data, $signature)
    {
        $certs = array();
        openssl_pkcs12_read(file_get_contents("你的.pfx文件路径"), $certs, "password");
        if (!$certs) return;
        $result = (bool)openssl_verify($data, $signature, $certs['cert']); //openssl_verify验签成功返回1，失败0，错误返回-1
        return $result;
    }
}