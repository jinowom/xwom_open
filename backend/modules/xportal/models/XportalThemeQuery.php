<?php

namespace backend\modules\xportal\models;

/**
 * This is the ActiveQuery class for [[XportalTheme]].
 *
 * @see XportalTheme
 */
class XportalThemeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XportalTheme[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XportalTheme|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
