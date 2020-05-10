<?php
namespace moxuandi\helpers;

/**
 * Class Helper 通用助手类
 *
 * @author zhangmoxuan <1104984259@qq.com>
 * @link http://www.zhangmoxuan.com
 * @QQ 1104984259
 * @Date 2019-2-4
 */
class Helper
{
    /**
     * 判断当前服务器操作系统.
     * @param string $linux Linux的标识.
     * @param string $windows Windows的标识.
     * @return string
     */
    public static function getOS($linux = 'Linux', $windows = 'Windows')
    {
        return PATH_SEPARATOR == ':' ? $linux : $windows;
    }

    /**
     * 获取当前微妙数.
     * @param bool $array 是否返回数组.
     * @return array|float
     */
    public static function microTimeFloat($array = false)
    {
        list($milli, $second) = explode(' ', microtime());
        return $array ? [$milli, $second] : ((float)$milli + (float)$second);
    }

    /**
     * 格式化文件大小.
     * @param int $size 文件大小, 单位:B, eg:1532684.
     * @param int $dec 小数位数, eg: 2.
     * @return string 格式化后的文件大小, eg: '1.46 MB'.
     */
    public static function byteFormat($size, $dec = 2)
    {
        $byte = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB', 'BB', 'NB', 'DB', 'CB'];
        $pos = 0;
        while($size >= 1024){
            $size /= 1024;
            $pos ++;
        }
        return round($size, $dec) . ' ' . $byte[$pos];
    }

    /**
     * 获取图片的宽高等属性.
     * @param string $imgPath 图片路径.
     * @param bool $array 是否返回数组.
     * @return array|bool|string
     */
    public static function getImageInfo($imgPath, $array = false)
    {
        if($imgInfo = getimagesize($imgPath)){
            if($array){
                return [
                    'width' => $imgInfo[0],
                    'height' => $imgInfo[1],
                    'type' => image_type_to_extension($imgInfo[2], false),
                    'mime' => $imgInfo['mime'],
                    'attr' => $imgInfo[3],
                ];
            }else{
                return $imgInfo[3];
            }
        }
        return false;
    }

    /**
     * 获取文件的扩展名.
     * @param string $fileName 文件名, eg: 'uploads/image/201707/1512001416.jpg'.
     * @return string 文件扩展名, eg: 'jpg'.
     */
    public static function getExtension($fileName)
    {
        //return strtolower(strrchr($fileName, '.'));  // return '.jpg'
        //return strtolower(substr(strrchr($fileName, '.'), 1));  // return 'jpg'
        return strtolower(substr($fileName, strrpos($fileName, '.') + 1));  // return 'jpg'
    }

    /**
     * 获取指定格式的文件路径.
     * @param string $fileName 原始文件名, eg: 'img.jpg'.
     * @param string $format 路径格式, eg: 'uploads/image/{yyyy}{mm}/{time}'.
     * @param string $fileType 文件扩展名, 默认使用`$fileName`的扩展名, eg: 'jpg'.
     * @return string 文件路径, eg: 'uploads/image/201707/1512001416.jpg'.
     * $format 可用变量:
     * - `{filename}`: 会替换成原文件名[要注意中文文件乱码问题]
     * - `{rand:6}`: 会替换成随机数, 后面的数字是随机数的位数
     * - `{time}`: 会替换成时间戳
     * - `{yyyy}`: 会替换成四位年份
     * - `{yy}`: 会替换成两位年份
     * - `{mm}`: 会替换成两位月份
     * - `{dd}`: 会替换成两位日期
     * - `{hh}`: 会替换成两位小时
     * - `{ii}`: 会替换成两位分钟
     * - `{ss}`: 会替换成两位秒
     * - 非法字符 \ : * ? " < > |
     * 具体请看线上文档: http://fex.baidu.com/ueditor/#server-path #3.1
     */
    public static function getFullName($fileName, $format, $fileType = '')
    {
        // 替换日期时间
        $time = time();
        $d = explode('-', date('Y-y-m-d-H-i-s', $time));
        $format = str_replace('{yyyy}', $d[0], $format);
        $format = str_replace('{yy}', $d[1], $format);
        $format = str_replace('{mm}', $d[2], $format);
        $format = str_replace('{dd}', $d[3], $format);
        $format = str_replace('{hh}', $d[4], $format);
        $format = str_replace('{ii}', $d[5], $format);
        $format = str_replace('{ss}', $d[6], $format);
        $format = str_replace('{time}', $time, $format);

        // 过滤文件名的非法字符, 并替换文件名
        $realName = substr($fileName, 0, strrpos($fileName, '.'));
        $realName = preg_replace("/[\|\?\"\<\>\/\*\\\\]+/", '', $realName);
        $format = str_replace('{filename}', $realName, $format);

        // 替换随机字符串
        //$randNum = rand(1, 10000000000) . rand(1, 10000000000);
        $randNum = mt_rand() . mt_rand();
        if(preg_match("/\{rand\:([\d]*)\}/i", $format, $matches)){
            $format = preg_replace("/\{rand\:[\d]*\}/i", substr($randNum, 0, $matches[1]), $format);
        }

        return $format . '.' . ($fileType ? $fileType : self::getExtension($fileName));
    }
}
