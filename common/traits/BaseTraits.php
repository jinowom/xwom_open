<?php
namespace common\traits;
use yii\helpers\ArrayHelper;

/**
 * 公共方法
 * Created by PhpStorm.
 * User: WANGWEIHUA
 */
trait BaseTraits
{

    /**
     * @Function 获取当前登录人的user_id
     * @Author: Weihuaadmin@163.com
     */
    public static function GetUserId(){
        return \Yii::$app->getUser()->id;
    }

    /**
     * @Function 获取当前登录用户的信息
     * @param  $param
     * @Author Weihuaadmin@163.com
     */
    public static function GetUserParam($param){
        return \Yii::$app->getUser()->getIdentity()->$param;
    }

    /**
     * @Function 是否超管管理员
     * @Author Weihuaadmin@163.com
     * @return boolean true AND false
     */
    public static function IsSuperAdmin(){
        $authManager = \Yii::$app->getAuthManager();
        $roles = array_keys($authManager->getRolesByUser(self::GetUserId()));
        return ArrayHelper::isIn($authManager->superRole,$roles);
    }


    /**
     * 兼容each函数在其他PHP版本下的异常
     * @Author: Weihuaadmin@163.com
     */
    public static function funEach(&$array){
        $res = [];
        $key = key($array);
        if($key !== null){
            next($array);
            $res[1] = $res['value'] = $array[$key];
            $res[0] = $res['key'] = $key;
        }else{
            $res = false;
        }
        return $res;
    }

    /**
     * 兼容count函数在其他PHP版本下的异常
     * @Author: Weihuaadmin@163.com
     */
    public static function funCount($array_or_countable,$mode = COUNT_NORMAL){
        $res = 0;
        if(is_array($array_or_countable) || is_object($array_or_countable)){
            $res = count($array_or_countable, $mode);
        }
        return $res;
    }
    
    /**
     * @Function 字数统计
     * @Author Weihuaadmin@163.com
     * @param $content
     * @return bool|int
     */
    public static function wordCount($content){
        $content = strip_tags($content);
        return empty($content) ? 0 : mb_strlen($content);
    }

}