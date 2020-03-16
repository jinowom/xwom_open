<?php

namespace common\models\config;

/**
 * This is the ActiveQuery class for [[ConfigMutillang]].
 *
 * @see ConfigMutillang
 */
class ConfigMutillangQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ConfigMutillang[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ConfigMutillang|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
