<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-7-11
 * Time: 下午5:14
 */

namespace wodrow\yii2wtools\tools;


use yii\log\Logger;

class Tools
{
    /**
     * 调试输出|调试模式下
     * @param  mixed $test 调试变量
     * @param  int $style 模式
     * @param  int $stop 是否停止
     * @return void       浏览器输出
     * @author wodrow <wodrow451611cv@gmail.com | 1173957281@qq.com>
     */
    public static function _vp($test, $stop = 0, $style = 0)
    {
        $outDir = \Yii::getAlias('@runtime');
        switch ($style) {
            case 0:
                echo "<pre>";
                echo "<br><hr>";
                var_dump($test);
                echo "</pre>";
                break;
            case 1:
                echo "<pre>";
                echo "<br><hr>";
                var_dump($test);
                echo "<hr/>";
                for ($i = 0; $i < 100; $i++) {
                    echo $i . "<hr/>";
                }
                echo "</pre>";
                break;
            case 2:
                file_put_contents($outDir . '/OUT.md', "\r" . var_export($test, true));
                break;
            case 3:
                file_put_contents($outDir . '/OUT.md', "\r\r" . var_export($test, true), FILE_APPEND);
                break;
        }
        if ($stop != 0) {
            exit("<hr/>");
        }
    }

    public static function log($msg, $log_name = "app")
    {
        $log = New \yii\log\FileTarget();
        $log->logFile = \Yii::$app->getRuntimePath() . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR . "{$log_name}.log";
        $log->messages[] = [$msg, Logger::LEVEL_INFO, 'tool-log', time()];
        $log->export();
    }

    public static function http_post($url, $param, $post_file = false)
    {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (is_string($param) || $post_file) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach ($param as $key => $val) {
                $aPOST[] = $key . "=" . urlencode($val);
            }
            $strPOST = join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
//			var_dump($aStatus);
            return $sContent;
        } else {
            return false;
        }
    }

    public static function getOutCache($data)
    {
        ob_start();
        var_dump($data);
        $x = ob_get_contents();
        ob_end_clean();
        return $x;
    }

    /**
     * 由身份证获取性别和出生年月日
     * @param $card
     * @return array
     */
    public static function getSexBirthFromIDCard($card)
    {

        $birth = substr($card, 6, 8);
        if (15 == strlen($card)) {
            $sex = substr($card, 15, 1) % 2 == 0 ? '女' : '男';
        } else {
            $sex = substr($card, 16, 1) % 2 == 0 ? '女' : '男';
        }
        return array(
            'birth' => $birth,
            'sex' => $sex
        );
    }

    /**
     * 检测网络是否连接
     * @param $url
     * @return bool
     */
    public static function varifyUrl($url)
    {
        $check = @fopen($url, "r");
        if ($check) {
            $status = true;
        } else {
            $status = false;
        }
        return $status;
    }


    /**
     * 阿拉伯数字金额转汉字大写
     *@param String Int $num 要转换的小写数字或小写字符串
     *@returnreturn 大写汉字
     *小数位为两位
     */
    public static function num_to_rmb($num){
        $c1 = "零壹贰叁肆伍陆柒捌玖";
        $c2 = "分角元拾佰仟万拾佰仟亿";
        //精确到分后面就不要了，所以只留两个小数位
        $num = round($num, 2);
        //将数字转化为整数
        $num = $num * 100;
        if (strlen($num) > 10) {
            return "金额太大，请检查";
        }
        $i = 0;
        $c = "";
        while (1) {
            if ($i == 0) {
                //获取最后一位数字
                $n = substr($num, strlen($num)-1, 1);
            } else {
                $n = $num % 10;
            }
            //每次将最后一位数字转化为中文
            $p1 = substr($c1, 3 * $n, 3);
            $p2 = substr($c2, 3 * $i, 3);
            if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
                $c = $p1 . $p2 . $c;
            } else {
                $c = $p1 . $c;
            }
            $i = $i + 1;
            //去掉数字最后一位了
            $num = $num / 10;
            $num = (int)$num;
            //结束循环
            if ($num == 0) {
                break;
            }
        }
        $j = 0;
        $slen = strlen($c);
        while ($j < $slen) {
            //utf8一个汉字相当3个字符
            $m = substr($c, $j, 6);
            //处理数字中很多0的情况,每次循环去掉一个汉字“零”
            if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
                $left = substr($c, 0, $j);
                $right = substr($c, $j + 3);
                $c = $left . $right;
                $j = $j-3;
                $slen = $slen-3;
            }
            $j = $j + 3;
        }
        //这个是为了去掉类似23.0中最后一个“零”字
        if (substr($c, strlen($c)-3, 3) == '零') {
            $c = substr($c, 0, strlen($c)-3);
        }
        //将处理的汉字加上“整”
        if (empty($c)) {
            return "零元整";
        }else{
            return $c . "整";
        }
    }
}