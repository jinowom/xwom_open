<?php
namespace wodrow\yii2wtools\tools;


use common\components\tools\ArrayHelper;

class Tree extends ArrayHelper
{
    /**
     * 把返回的数据集转换成Tree
     * @param array $list 要转换的数据集
     * @param string $pid parent标记字段
     * @param string $level level标记字段
     * @return array
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public static function list2tree($list, $root = 0, $pk='id', $pid = 'pid', $child = '_child') {
        // 创建Tree
        $tree = [];
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = [];
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }
    /**
     * 将list2tree的树还原成列表
     * @param  array $tree  原来的树
     * @param  string $child 孩子节点的键
     * @param  string $order 排序显示的键，一般是主键 升序排列
     * @param  array  $list  过渡用的中间数组，
     * @return array        返回排过序的列表数组
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public static function tree2list($tree, $child = '_child', $order='id', &$list = []){
        if(is_array($tree)) {
            $refer = [];
            foreach ($tree as $key => $value) {
                $reffer = $value;
                if(isset($reffer[$child])){
                    unset($reffer[$child]);
                    self::tree2list($value[$child], $child, $order, $list);
                }
                $list[] = $reffer;
            }
            $list = self::list_sort_by($list, $order, $sortby='asc');
        }
        return $list;
    }

    /**
     * 对查询结果集进行排序
     * @access public
     * @param array $list 查询结果
     * @param string $field 排序的字段名
     * @param array $sortby 排序类型
     * asc正向排序 desc逆向排序 nat自然排序
     * @return array|bool
     */
    public static function list_sort_by($list, $field, $sortby = 'asc')
    {
        if (is_array($list)) {
            $refer = $resultSet = array();
            foreach ($list as $i => $data)
                $refer[$i] = &$data[$field];
            switch ($sortby) {
                case 'asc': // 正向排序
                    asort($refer);
                    break;
                case 'desc':// 逆向排序
                    arsort($refer);
                    break;
                case 'nat': // 自然排序
                    natcasesort($refer);
                    break;
            }
            foreach ($refer as $key => $val)
                $resultSet[] = &$list[$key];
            return $resultSet;
        }
        return false;
    }

    /**
     * 获取节点所有父级元素
     * @param array $tree 数据
     * @param int $node_id 节点id
     * @param int $pk 主键
     * @param int $pid 外键
     * @param int $root 根节点
     * @return array 父节点
     * @author wodrow <wodrow451611cv@gmail.com | 1173957281@qq.com>
     */
    public static function get_list_parents($tree, $node_id, $pk='id', $pid='pid', $root = 0)
    {
        $i = $root;
        while($node_id != $root){
            foreach ($tree as $k => $v){
                if ($v[$pk] == $node_id){
                    $node_to_root[$i++] = $k;
                    $node_id = $v[$pid];
                }
            }
        }
        $i = $i-1;
        for($i;$i>=$root;$i--){
            $root_to_node[] = $node_to_root[$i];
            $parent_list[] = $tree[$node_to_root[$i]];
        }
        return $parent_list;
    }

    /**
     * 获取带节点排序的数据
     * @param array $tree 数据
     * @param int $start 起始值
     * @param string $child_name 子级键名
     * @param string $sort_name 排序字段下标
     * @author wodrow <wodrow451611cv@gmail.com | 1173957281@qq.com>
     */
    public static function get_tree_node_sort(&$tree, $start = 0, $child_name = '_child', $sort_name = '_node_sort')
    {
        foreach ($tree as $k => $v){
            $tree[$k][$sort_name] = $start;
            if (isset($tree[$k][$child_name])&&$tree[$k][$child_name]){
                $start ++;
                self::get_tree_node_sort($tree[$k][$child_name], $start, $child_name, $sort_name);
                $start --;
            }
        }
    }

    /**
     * 树节点前样式数据
     * @param $tree
     * @param string $styles_key
     * @param array $styles
     * @param string $child_name
     * @param string $sort_name
     */
    public static function getPreStyle(&$tree, $styles_key = '_styles', $styles = [], $child_name = '_child', $sort_name = '_node_sort')
    {
        foreach ($tree as $k => $v){
            $styles[$tree[$k][$sort_name]] = 2;
            $tree[$k][$styles_key] = $styles;
            if (isset($tree[$k][$child_name])){
                if (isset($tree[$k+1])){
                    $styles[$tree[$k][$sort_name]] = 1;
                }else{
                    $styles[$tree[$k][$sort_name]] = 0;
                }
                self::getPreStyle($tree[$k][$child_name], $styles_key, $styles, $child_name, $sort_name);
            }
        }
    }
}