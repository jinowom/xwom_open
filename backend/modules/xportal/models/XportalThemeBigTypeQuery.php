<?php

namespace backend\modules\xportal\models;

/**
 * This is the ActiveQuery class for [[XportalThemeBigType]].
 *
 * @see XportalThemeBigType
 */
class XportalThemeBigTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XportalThemeBigType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XportalThemeBigType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
