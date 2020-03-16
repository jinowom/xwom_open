<?php

namespace backend\modules\xportal\models;

/**
 * This is the ActiveQuery class for [[XportalThemeType]].
 *
 * @see XportalThemeType
 */
class XportalThemeTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XportalThemeType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XportalThemeType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
