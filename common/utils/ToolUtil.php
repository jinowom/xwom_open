<?php
namespace common\utils;


use yii\helpers\ArrayHelper;
use \yii\helpers\Url;

class ToolUtil
{
    /**
     * @Function: 获取日期格式，多用于显示数据
     * @Author: Weihuaadmin@163.com
     * @param $value
     * @param string $format
     * @param string $default
     * @return bool|string
     */
    public static function getDate($value, $format='Y-m-d', $default='')
    {
        return $value == 0 ? $default : date($format, $value);
    }

    /**
     * @Function 判断isset值
     * @Author Weihuaadmin@163.com
     * @param $value
     * @param string $default
     * @return string
     */
    public static function getIsset($value,$default = ''){
        return isset($value) ? $value : $default;
    }

    /**
     * @Function: 获取select名称
     * @param $option
     * @param $id
     * @param string $default
     * @return string
     */
    public static function getSelectType($option, $id, $default = '')
    {
        if (isset($option[$id])) {
            return $option[$id];
        } else {
            return $default;
        }
    }

    /**
     * @Function:判断是否ajax请求
     * @Author: Weihuaadmin@163.com
     * @return bool
     */
    public static function isAjax()
    {
        return \Yii::$app->request->getIsAjax();
    }


    /**
     * @Function:判断当前请求是否是POST方式
     * @Author: Weihuaadmin@163.com
     * @return bool
     */
    public static function isPost()
    {
        return \Yii::$app->request->getIsPost();
    }

    /**
     * @Function: 判断当前请求是否是Get方式
     * @Author: Weihuaadmin@163.com
     * @return bool
     */
    public static function isGet()
    {
        return \Yii::$app->request->getIsGet();
    }

    /**
     * @Function: 统一ajax返回信息
     * @Author:Weihuaadmin@163.com
     * @param bool $status
     * @param string $msg
     * @param array $expandArr 拓展返回值
     * @return array
     */
    public static function returnAjaxMsg($status, $msg, $expandArr = [])
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ArrayHelper::merge([
            'status' => $status,
            'msg' => $msg == '' ? ($status ? '操作成功!' : '操作失败!') : $msg,
        ],$expandArr);
    }

    /**
     * @Function: 统一返回信息
     * @Author:Weihuaadmin@163.com
     * @param bool $status
     * @param string $msg
     * @param array $expandArr 拓展返回值
     * @return array
     */
    public static function returnMsg($status, $msg = '', $expandArr = [])
    {
        return ArrayHelper::merge([
            'status' => $status,
            'msg' => $msg == '' ? ($status ? '操作成功!' : '操作失败!') : $msg,
        ],$expandArr);
    }

    /**
     * 格式化结果
     * @Author: Weihuaadmin@163.com
     * @param $permissions
     */
    public static function arrToTree($permissions,$pId, $level = 0, $pKey = 'parentName',$key = 'name',$filterKey = 'isMenu'){
        $tree = [];
        $level++;
        foreach ($permissions as $permission){
            $permission['level'] = $level;
            if(empty($permission[$filterKey])){
                continue;
            }
            if($permission[$pKey] == $pId){
                $permission['children'] = self::arrToTree($permissions,$permission[$key],$level,$pKey,$key);
                $tree[] = $permission;
            }
        }
        return $tree;
    }

    /**
     * @Function 俩值比较
     * @Author Weihuaadmin@163.com
     * @param $val 值
     * @param $valT 值2
     * @param string $eqDefault 相同时
     * @param string $neqDefault 不同时
     * @return string
     */
    public static function valCompareVal($val,$valT,$eqDefault = '',$neqDefault=''){
        return ($val == $valT) ? $eqDefault : $neqDefault;
    }

    /**
     * @Function 无限递归输出菜单
     * @Author Weihuaadmin@163.com
     * @param $lists
     * @return string
     */
    public static function menuListHtml($lists){
        $h = '';
        foreach ($lists as $k => $list){
//            $class = ($k == 0 ** empty($list['parentName']) && $list['level']== 1) ? "open" : '';
            $h .= "<li class=''>";
            if(empty($list['parentName']) && empty($list['children'])){
                $h .= '<a href="javascript:;"><i class="iconfont left-nav-li" lay-tips="'.$list["description"].'"></i>';
                $h .= '<cite>'.$list["description"].'</cite></a>';
            }else{
                if($list['children']){
                    $px = $list['level'] * 20;
                    $h .= '<a href="javascript:;" style="padding-left: '.$px.'px;">';
                    if(empty($list['parentName'])){
                        $h .= '<i class="iconfont left-nav-li" lay-tips="'.$list["description"].'">&#xe6b8;</i>';
                    }else{
                        $h .= '<i class="layui-icon '.$list['icon'].'" lay-tips="'.$list["description"].'"></i>';
                    }
                    $h .= '<cite>'.$list["description"].'</cite>' ;
                    $h .= "<i class='iconfont nav_right'></i>";
                    $h .= "</a>";
                    $h .= '<ul class="sub-menu">';
                    $h .= self::menuListHtml($list['children']);
                    $h .= '</ul>';
                }else{
                    $px = $list['level'] * 20;
                    $toRoute = Url::toRoute($list['name']);
                    $icon = !empty($list['icon']) ? $list['icon'] : 'layui-icon-left';
                    $h .= '<a style="padding-left: '.$px.'px;" onclick="xadmin.add_tab(\''.$list["description"].'\',\''.$toRoute.'\')"><i class="layui-icon '.$icon.'"></i>';
                    $h .= '<cite>'.$list["description"].'</cite></a>';
                }
            }
            $h .= "</li>";
        }
        return $h;
    }
}