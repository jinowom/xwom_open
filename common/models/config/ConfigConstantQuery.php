<?php

namespace common\models\config;

/**
 * This is the ActiveQuery class for [[ConfigConstant]].
 *
 * @see ConfigConstant
 */
class ConfigConstantQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ConfigConstant[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ConfigConstant|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
