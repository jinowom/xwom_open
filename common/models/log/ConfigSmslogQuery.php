<?php

namespace common\models\log;

/**
 * This is the ActiveQuery class for [[ConfigSmslog]].
 *
 * @see ConfigSmslog
 */
class ConfigSmslogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ConfigSmslog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ConfigSmslog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
