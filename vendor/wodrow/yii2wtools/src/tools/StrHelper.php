<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-7-11
 * Time: 下午5:35
 */

namespace wodrow\yii2wtools\tools;


class StrHelper
{
    public static function strToHex($string)//字符串转十六进制
    {
        $hex = "";
        for ($i = 0; $i < strlen($string); $i++)
            $hex .= dechex(ord($string[$i]));
        $hex = strtoupper($hex);
        return $hex;
    }

    public static function hexToStr($hex)//十六进制转字符串
    {
        $string = "";
        for ($i = 0; $i < strlen($hex) - 1; $i += 2)
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        return $string;

    }

    public static function getBytes($string)
    {
        $bytes = array();
        for ($i = 0; $i < strlen($string); $i++) {
            $bytes[] = ord($string[$i]);
        }
        return $bytes;
    }

    public static function asc2bin($temp)
    {
        $data = '';
        $len = strlen($temp);
        for ($i = 0; $i < $len; $i++) $data .= sprintf("%08b", ord(substr($temp, $i, 1)));
        return $data;
    }

    /**
     *
     * ASCII 转 十六进制 以及 十六进制 转 ASCII
     * 非盈利组织或个人请放心转载，商业用途请征得作者同意
     *
     */
    //ASCII 转 十六进制
    public static function asc2hex($str)
    {
        return '\x' . substr(chunk_split(bin2hex($str), 2, '\x'), 0, -2);
    }

    //十六进制 转 ASCII
    public static function hex2asc($str)
    {
        $data = '';
        $str = join('', explode('\x', $str));
        $len = strlen($str);
        for ($i = 0; $i < $len; $i += 2) $data .= chr(hexdec(substr($str, $i, 2)));
        return $data;
    }

    /**
     * 转换一个String字符串为byte数组(10进制)
     * @param $str 需要转换的字符串
     * @param $bytes 目标byte数组
     * @author Zikie
     */

    public static function getBytes_10($str)
    {


        $len = strlen($str);
        $bytes = array();
        for ($i = 0; $i < $len; $i++) {
            if (ord($str[$i]) >= 128) {
                $byte = ord($str[$i]) - 256;
            } else {
                $byte = ord($str[$i]);
            }
            $bytes[] = $byte;
        }
        return $bytes;
    }


    /**
     * 转换一个String字符串为byte数组(16进制)
     * @param $str 需要转换的字符串
     * @param $bytes 目标byte数组
     * @author Zikie
     */

    public static function getBytes_16($str)
    {


        $len = strlen($str);
        $bytes = array();
        for ($i = 0; $i < $len; $i++) {
            if (ord($str[$i]) >= 128) {
                $byte = ord($str[$i]) - 256;
            } else {
                $byte = ord($str[$i]);
            }
            $bytes[] = "0x" . dechex($byte);
        }
        return $bytes;
    }


    /**
     * 转换一个String字符串为byte数组(2进制)
     * @param $str 需要转换的字符串
     * @param $bytes 目标byte数组
     * @author Zikie
     */

    public static function StrToBin($str)
    {
        //1.列出每个字符
        $arr = preg_split('/(?<!^)(?!$)/u', $str);
        //2.unpack字符
        foreach ($arr as &$v) {
            $temp = unpack('H*', $v);
            $v = base_convert($temp[1], 16, 2);
            unset($temp);
        }

        return $arr;
    }


    /**
     * 转换一个byte数组为String(2进制)
     * @param $str 需要转换的字符串
     * @param $bytes 目标byte数组
     * @author Zikie
     */

    function BinToStr($str)
    {
        $arr = explode(' ', $str);
        foreach ($arr as &$v) {
            $v = pack("H" . strlen(base_convert($v, 2, 16)), base_convert($v, 2, 16));
        }

        return $v;
    }

    /**
     * 将字节数组转化为String类型的数据
     * @param $bytes 字节数组
     * @param $str 目标字符串
     * @return 一个String类型的数据
     */

    public static function toStr($bytes)
    {
        $str = '';
        foreach ($bytes as $ch) {
            $str .= chr($ch);
        }


        return $str;
    }

    /**
     * 转换一个int为byte数组
     * @param $byt 目标byte数组
     * @param $val 需要转换的字符串
     * @author Zikie
     */

    public static function integerToBytes($val)
    {
        $byt = array();
        $byt[0] = ($val & 0xff);
        $byt[1] = ($val >> 8 & 0xff);
        $byt[2] = ($val >> 16 & 0xff);
        $byt[3] = ($val >> 24 & 0xff);
        return $byt;
    }

    /**
     * 从字节数组中指定的位置读取一个Integer类型的数据
     * @param $bytes 字节数组
     * @param $position 指定的开始位置
     * @return 一个Integer类型的数据
     */

    public static function bytesToInteger($bytes, $position)
    {
        $val = 0;
        $val = $bytes[$position + 3] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position + 2] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position + 1] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position] & 0xff;
        return $val;
    }


    /**
     * 转换一个shor字符串为byte数组
     * @param $byt 目标byte数组
     * @param $val 需要转换的字符串
     * @author Zikie
     */

    public static function shortToBytes($val)
    {
        $byt = array();
        $byt[0] = ($val & 0xff);
        $byt[1] = ($val >> 8 & 0xff);
        return $byt;
    }

    /**
     * 从字节数组中指定的位置读取一个Short类型的数据。
     * @param $bytes 字节数组
     * @param $position 指定的开始位置
     * @return 一个Short类型的数据
     */

    public static function bytesToShort($bytes, $position)
    {
        $val = 0;
        $val = $bytes[$position + 1] & 0xFF;
        $val = $val << 8;
        $val |= $bytes[$position] & 0xFF;
        return $val;
    }

    /**
     * 字符串转换为数组，主要用于把分隔符调整到第二个参数
     * @param  string $str 要分割的字符串
     * @param  string $glue 分割符
     * @return array
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public static function str2arr($str, $glue = ',')
    {
        return explode($glue, $str);
    }

    /**
     * 数组转换为字符串，主要用于把分隔符调整到第二个参数
     * @param  array $arr 要连接的数组
     * @param  string $glue 分割符
     * @return string
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public static function arr2str($arr, $glue = ',')
    {
        return implode($glue, $arr);
    }

    /**
     * 去空格
     * @param $str
     * @return mixed
     */
    public static function trimall($str)
    {
        $qian = array(" ", "　", "\t", "\n", "\r");
        return str_replace($qian, '', $str);
    }

    /**
     * 字符串中是数组格式化为数组
     * @param $data
     * @return array
     */
    public static function formatArrStrToArr($data)
    {
        eval("\$data = " . $data . '; ');
        return $data;
    }
}