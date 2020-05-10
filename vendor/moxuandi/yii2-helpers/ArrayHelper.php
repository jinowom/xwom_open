<?php
namespace moxuandi\helpers;

/**
 * Class ArrayHelper 拓展数组助手类
 *
 * @author zhangmoxuan <1104984259@qq.com>
 * @link http://www.zhangmoxuan.com
 * @QQ 1104984259
 * @Date 2019-8-4
 */
class ArrayHelper extends \yii\helpers\ArrayHelper
{
    /**
     * 递归数组
     * @param array $items 传入的数组
     * @param int $pid 父级ID
     * @param string $id ID字段名
     * @param string $pidName 父级ID字段名
     * @param string $itemKey 子数组的键名
     * @param bool $ignoreEmpty 是否忽略空子集
     * @return array
     */
    public static function itemsMerge(array $items, $pid = 0, $id = 'id', $pidName = 'pid', $itemKey = '-', $ignoreEmpty = false)
    {
        $array = [];
        foreach($items as $item){
            if($item[$pidName] == $pid){
                $children = self::itemsMerge($items, $item[$id], $id, $pidName, $itemKey, $ignoreEmpty);
                if($children || !$ignoreEmpty){
                    $item[$itemKey] = $children;
                }
                $array[] = $item;
            }
        }
        return $array;
    }

    /**
     * 根据级别和数组返回字符串
     * @param array $items `itemsMerge()`方法处理后的数组
     * @param int $level 级别
     * @param int $key 数组键名
     * @return bool|string
     */
    public static function itemsLevel(array $items, $level, $key)
    {
        $str = '';
        for($i = 1; $i < $level; $i++){
            $str .= '　　';  // 全角空格
            if($i == $level - 1){
                if(isset($items[$key + 1])){
                    return $str . '├──';  // 制表符
                }
                return $str . '└──';  // 制表符
            }
        }
        return false;
    }

    /**
     * 处理递归数组为二维数组
     * @param array $array 引用传值返回
     * @param array $items `itemsMerge()`方法处理后的数组
     * @param string|false $titleName 要处理的键名
     */
    public static function itemsMergeLevel(&$array, array $items, $titleName = 'title')
    {
        foreach($items as $key => $item){
            $children = self::remove($item, '-', []);
            if($titleName){
                $item[$titleName] = self::itemsLevel($items, $item['level'], $key) . $item[$titleName];
            }
            $array[$item['id']] = $item;
            self::itemsMergeLevel($array, $children, $titleName);
        }
    }
}
