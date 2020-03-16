<?php

namespace backend\modules\xportal\models;

/**
 * This is the ActiveQuery class for [[XportalNews]].
 *
 * @see XportalNews
 */
class XportalNewsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XportalNews[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XportalNews|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
