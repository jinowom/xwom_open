<?php
namespace common\traits;

use yii\db\ActiveQuery;
use yii\db\ExpressionInterface;
use yii\helpers\ArrayHelper;

trait MapTrait
{
    /**
     * @param $key
     * @param $value
     * @param array $condition
     * @param string|array|null|ExpressionInterface $orderBy
     * @return array
     */
    public static function map($key,$value,$condition = [],$orderBy = null)
    {
        /**
         * @var $query ActiveQuery
         */
        $query = self::find()->where($condition);

        if($orderBy){
            $query->orderBy($orderBy);
        }
        return ArrayHelper::map($query->all(), $key, $value);
    }
}