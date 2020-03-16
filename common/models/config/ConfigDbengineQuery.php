<?php

namespace common\models\config;

/**
 * This is the ActiveQuery class for [[ConfigDbengine]].
 *
 * @see ConfigDbengine
 */
class ConfigDbengineQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ConfigDbengine[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ConfigDbengine|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
