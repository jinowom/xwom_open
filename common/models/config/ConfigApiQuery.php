<?php

namespace common\models\config;

/**
 * This is the ActiveQuery class for [[ConfigApi]].
 *
 * @see ConfigApi
 */
class ConfigApiQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ConfigApi[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ConfigApi|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
