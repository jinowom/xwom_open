<?php

namespace backend\modules\xportal\models;

/**
 * This is the ActiveQuery class for [[XportalChannelCategoryTree]].
 *
 * @see XportalChannelCategoryTree
 */
class XportalChannelCategoryTreeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XportalChannelCategoryTree[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XportalChannelCategoryTree|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
