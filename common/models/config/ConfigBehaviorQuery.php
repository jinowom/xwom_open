<?php

namespace common\models\config;

/**
 * This is the ActiveQuery class for [[ConfigBehavior]].
 *
 * @see ConfigBehavior
 */
class ConfigBehaviorQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ConfigBehavior[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ConfigBehavior|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
