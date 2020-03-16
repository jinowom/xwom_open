<?php

namespace common\models\config;

/**
 * This is the ActiveQuery class for [[ConfigEmail]].
 *
 * @see ConfigEmail
 */
class ConfigEmailQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ConfigEmail[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ConfigEmail|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
