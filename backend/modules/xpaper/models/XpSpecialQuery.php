<?php

namespace backend\modules\xpaper\models;

/**
 * This is the ActiveQuery class for [[XpSpecial]].
 *
 * @see XpSpecial
 */
class XpSpecialQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XpSpecial[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XpSpecial|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
