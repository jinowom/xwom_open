<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-7-11
 * Time: 下午5:32
 */

namespace wodrow\yii2wtools\tools;


class ArrayHelper extends \yii\helpers\ArrayHelper
{
    public static function objectToArray($obj)
    {
        $arr = is_object($obj) ? get_object_vars($obj) : $obj;
        if (is_array($arr)) {
            return array_map(__METHOD__, $arr);
        } else {
            return $arr;
        }
    }

    public static function arrayToObject($arr)
    {
        if (is_array($arr)) {
            return (object)array_map(__METHOD__, $arr);
        } else {
            return $arr;
        }
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
     * 数组转xml
     * @param $arr
     * @param int $dom
     * @param int $item
     * @return string
     */
    public static function arrayToXml($arr, $root_name = 'root', $dom = 0, $item = 0, $version = "1.0", $unicode = "utf-8")
    {
        if (!$dom) {
            $dom = new \DOMDocument($version, $unicode);
        }
        if (!$item) {
            $item = $dom->createElement($root_name);
            $dom->appendChild($item);
        }
        foreach ($arr as $key => $val) {
            $itemx = $dom->createElement(is_string($key) ? $key : "item");
            $item->appendChild($itemx);
            if (!is_array($val)) {
                $text = $dom->createTextNode($val);
                $itemx->appendChild($text);

            } else {
                self::arrayToXml($val, $root_name, $dom, $itemx);
            }
        }
        return $dom->saveXML();
    }

    public static function arrayToXml1($arr, $root_name = null)
    {
        if ($root_name) {
            $xml = "<{$root_name}>";
        } else {
            $xml = '';
        };
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $xml .= "<" . $key . ">" . self::arrayToXml1($val) . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }
        if ($root_name) {
            $xml .= "</{$root_name}>";
        } else {
            $xml .= '';
        };
        return $xml;
    }

    /**
     * xml转数组
     * @param $xml
     * @return mixed
     */
    public static function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring), true);
        return $val;
    }
}