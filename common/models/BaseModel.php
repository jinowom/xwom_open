<?php
/**
 * Created by PhpStorm.
 * User: hh
 * Date: 2019/10/8
 * Time: 17:20
 */

namespace common\models;


use common\traits\BaseTraits;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class BaseModel extends ActiveRecord
{
    use BaseTraits;
    /**
     * 获取Model验证错误
     * @Author Weihuaadmin@163.com
     */
    public function getModelError(){
        $errors = self::getErrors();
        if(empty($errors)){
            return false;
        }
        $firstError = array_shift($errors);
        if(!is_array($firstError)) {
            return false;
        }
        return array_shift($firstError);
    }

    /**
     * @Function: 获取指定条件获取某个值
     * @Author: weihuaadmin@163.com
     * @Date: 2019/1/12 10:00
     * @param $where
     * @param $field
     * @return mixed
     */
    public static function findValueByWhere($where,  $field = [], $order = 'id desc')
    {
        $class = get_called_class();
        $field = !empty($field) ? $field : ['*'];
        $field = is_array($field) ? $field : explode(',',$field);
        $data = $class::find()
            ->where($where)
            ->select($field)
            ->orderBy($order)
            ->asArray()
            ->one();
        
        return self::funCount($field) == 1 && $field != ['*'] ? ArrayHelper::getValue($data, $field) : $data;
    }

    /**
     * @Function: 获取指定条件的数据
     * @Author: weihuaadmin@163.com
     * @Date: 2019/1/16 9:18
     * @param $where
     * @param array $field
     * @param string $order
     * @return mixed
     */
    public static function findAllByWhere($where, $field = [], $order = 'id desc')
    {
        $class = get_called_class();
        return $class::find()
            ->where($where)
            ->select($field)
            ->orderBy($order)
            ->asArray()
            ->all();
    }

    /**
     * @Function: 获取指定条件的数据[返回对象]
     * @Author: weihuaadmin@163.com
     * @Date: 2019/1/16 9:18
     * @param $where
     * @param array $field
     * @param string $order
     * @return object
     */
    public static function findAllByWhereObj($where, $field = [], $order = 'id desc')
    {
        $class = get_called_class();
        return $class::find()
            ->where($where)
            ->select($field)
            ->orderBy($order)
            ->all();
    }
}