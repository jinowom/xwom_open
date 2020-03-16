<?php

namespace common\models\config;

/**
 * This is the ActiveQuery class for [[ConfigBase]].
 *
 * @see ConfigBase
 */
class ConfigBaseQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ConfigBase[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ConfigBase|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
