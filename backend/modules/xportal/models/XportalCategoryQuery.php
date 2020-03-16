<?php

namespace backend\modules\xportal\models;

/**
 * This is the ActiveQuery class for [[XportalCategory]].
 *
 * @see XportalCategory
 */
class XportalCategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XportalCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XportalCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
