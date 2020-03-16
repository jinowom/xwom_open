<?php

namespace backend\modules\xportal\models;

/**
 * This is the ActiveQuery class for [[XportalCCC]].
 *
 * @see XportalCCC
 */
class XportalCCCQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XportalCCC[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XportalCCC|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
