<?php

namespace backend\modules\xportal\models;

/**
 * This is the ActiveQuery class for [[XportalBase]].
 *
 * @see XportalBase
 */
class XportalBaseQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XportalBase[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XportalBase|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
