<?php

namespace common\models\config;

/**
 * This is the ActiveQuery class for [[ConfigVariable]].
 *
 * @see ConfigVariable
 */
class ConfigVariableQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ConfigVariable[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ConfigVariable|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
