<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-7-11
 * Time: 下午5:39
 */

namespace wodrow\yii2wtools\tools;


class EncodeHelper
{
    public static function UnicodeEncode($str){
        //split word
        preg_match_all('/./u',$str,$matches);

        $unicodeStr = "";
        foreach($matches[0] as $m){
            //拼接
            $unicodeStr .= "&#".base_convert(bin2hex(iconv('UTF-8',"UCS-4",$m)),16,10);
        }
        return $unicodeStr;
    }

    public static function unicodeDecode($unicode_str){
        $json = '{"str":"'.$unicode_str.'"}';
        $arr = json_decode($json,true);
        if(empty($arr)) return '';
        return $arr['str'];
    }

    public static function unicode2utf8($str)
    {
        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', create_function('$matches', 'return iconv("UCS-2BE","UTF-8",pack("H*", $matches[1]));'), $str);
    }
}