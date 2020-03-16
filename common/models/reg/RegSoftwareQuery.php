<?php

namespace common\models\reg;

/**
 * This is the ActiveQuery class for [[RegSoftware]].
 *
 * @see RegSoftware
 */
class RegSoftwareQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RegSoftware[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RegSoftware|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
