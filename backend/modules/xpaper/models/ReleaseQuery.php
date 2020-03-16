<?php

namespace backend\modules\xpaper\models;

/**
 * This is the ActiveQuery class for [[Release]].
 *
 * @see Release
 */
class ReleaseQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Release[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Release|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
