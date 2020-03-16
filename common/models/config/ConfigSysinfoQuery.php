<?php

namespace common\models\config;

/**
 * This is the ActiveQuery class for [[ConfigSysinfo]].
 *
 * @see ConfigSysinfo
 */
class ConfigSysinfoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ConfigSysinfo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ConfigSysinfo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
