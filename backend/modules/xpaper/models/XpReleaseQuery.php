<?php

namespace backend\modules\xpaper\models;

/**
 * This is the ActiveQuery class for [[XpRelease]].
 *
 * @see XpRelease
 */
class XpReleaseQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XpRelease[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XpRelease|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
