<?php

namespace backend\modules\xpaper\models;

/**
 * This is the ActiveQuery class for [[XpTheme]].
 *
 * @see XpTheme
 */
class XpThemeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XpTheme[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XpTheme|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
