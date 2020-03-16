<?php

namespace common\models\config;

/**
 * This is the ActiveQuery class for [[ConfigIpmanage]].
 *
 * @see ConfigIpmanage
 */
class ConfigIpmanageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ConfigIpmanage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ConfigIpmanage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
