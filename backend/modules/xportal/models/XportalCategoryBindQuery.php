<?php

namespace backend\modules\xportal\models;

/**
 * This is the ActiveQuery class for [[XportalCategoryBind]].
 *
 * @see XportalCategoryBind
 */
class XportalCategoryBindQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XportalCategoryBind[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XportalCategoryBind|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
