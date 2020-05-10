<?php
namespace moxuandi\helpers;

/**
 * Class StringHelper 拓展字符串助手类
 *
 * @author zhangmoxuan <1104984259@qq.com>
 * @link http://www.zhangmoxuan.com
 * @QQ 1104984259
 * @Date 2019-8-4
 */
class StringHelper extends \yii\helpers\StringHelper
{
    /**
     * 格式化标题样式(字符串转数组)
     * @param string $style
     * @return array
     */
    public static function decodeStyle($style)
    {
        $styles = self::parseAttr($style);
        return [
            'font-weight' => [
                'label' => '加粗',
                'type' => 'checkbox',
                'value' => ArrayHelper::getValue($styles, 'font-weight') === 'bold',
            ],
            'text-decoration' => [
                'label' => '下划线',
                'type' => 'checkbox',
                'value' => ArrayHelper::getValue($styles, 'text-decoration') === 'underline',
            ],
            /*'font-size' => [
                'label' => '字号',
                'type' => 'dropDownList',
                'value' => ArrayHelper::getValue($styles, 'font-size', ''),
            ],*/
            'color' => [
                'label' => '颜色',
                'type' => 'colorPicker',  // 调用`KindEditor`的取色器
                'value' => ArrayHelper::getValue($styles, 'color', ''),
            ],
        ];
    }

    /**
     * 格式化标题样式(数组转字符串)
     * @param array $styles
     * @return string
     */
    public static function encodeStyle($styles = [])
    {
        $result = [];
        foreach($styles as $key => $value){
            if($value === '1'){
                switch($key){
                    case 'font-weight': $result[] = 'font-weight:bold'; break;
                    case 'text-decoration': $result[] = 'text-decoration:underline'; break;
                    default: break;
                }
            }elseif(!empty($value)){
                $result[] = $key . ':' . $value;
            }
        }
        return implode(';', $result);
    }

    /**
     * 分析枚举类型配置值
     * @param string $string 格式: `a:名称1,b:名称2`或`a:名称1;b:名称2`
     * @return array
     */
    public static function parseAttr($string)
    {
        $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));  // 使用正则表达式分割字符串成数组
        if(strpos($string,':')){  // 如果源字符串中有`:`
            $result = [];
            foreach($array as $val){
                list($key, $value) = explode(':', $val);  // 分割字符串成数组, 并赋值给变量
                $result[$key] = $value;
            }
        }else{
            $result = $array;
        }
        return $result;
    }
}
