<?php

namespace common\models\log;

/**
 * This is the ActiveQuery class for [[ConfigFunctionlog]].
 *
 * @see ConfigFunctionlog
 */
class ConfigFunctionlogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ConfigFunctionlog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ConfigFunctionlog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
