<?php

namespace backend\modules\xportal\models;

/**
 * This is the ActiveQuery class for [[XportalPushData]].
 *
 * @see XportalPushData
 */
class XportalPushDataQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XportalPushData[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XportalPushData|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
