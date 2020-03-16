<?php

namespace common\models\reg;

/**
 * This is the ActiveQuery class for [[RegModule]].
 *
 * @see RegModule
 */
class RegModuleQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RegModule[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RegModule|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
