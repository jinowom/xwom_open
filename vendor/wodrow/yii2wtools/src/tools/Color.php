<?php
namespace wodrow\yii2wtools\tools;


class Color
{
    /**
     * 颜色转十六进制
     * @param $colour
     * @return array|bool
     */
    public static function hex2rgb($colour, $rout = 'colour')
    {
        $str = "";
        if ($colour[0] == '#') {
            $colour = substr($colour, 1);
            $str = "#";
        }
        if (strlen($colour) == 6) {
            list($r, $g, $b) = array($colour[0] . $colour[1], $colour[2] .
                $colour[3], $colour[4] . $colour[5]);
        } elseif (strlen($colour) == 3) {
            list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] .
                $colour[1], $colour[2] . $colour[2]);
        } else {
            return false;
        }
        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);
        $arr = array('red' => $r, 'green' => $g, 'blue' => $b);
        $str = $str."{$r}{$g}{$b}";
        if ($rout == 'arr')return $arr;
        if ($rout == 'colour')return $str;
    }

    /**
     * 获取对比色
     * @param string $colour
     * @param string $rout colour|arr
     * @return array|bool|string
     */
    public static function rgb2contrast($colour, $rout = 'colour')
    {
        $str = "";
        if ($colour[0] == '#') {
            $colour = substr($colour, 1);
            $str = "#";
        }
        if (strlen($colour) == 6) {
            list($r, $g, $b) = array($colour[0] . $colour[1], $colour[2] .
                $colour[3], $colour[4] . $colour[5]);
        } elseif (strlen($colour) == 3) {
            list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] .
                $colour[1], $colour[2] . $colour[2]);
        } else {
            return false;
        }
        $r = 255 - hexdec($r);
        $g = 255 - hexdec($g);
        $b = 255 - hexdec($b);
        $r = dechex($r);
        $g = dechex($g);
        $b = dechex($b);
        $arr = array('red' => $r, 'green' => $g, 'blue' => $b);
        $str = $str."{$r}{$g}{$b}";
        if ($rout == 'arr')return $arr;
        if ($rout == 'colour')return $str;
    }
}