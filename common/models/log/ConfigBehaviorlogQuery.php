<?php

namespace common\models\log;

/**
 * This is the ActiveQuery class for [[ConfigBehaviorlog]].
 *
 * @see ConfigBehaviorlog
 */
class ConfigBehaviorlogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ConfigBehaviorlog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ConfigBehaviorlog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
