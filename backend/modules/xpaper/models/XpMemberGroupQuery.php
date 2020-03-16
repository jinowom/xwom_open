<?php

namespace backend\modules\xpaper\models;

/**
 * This is the ActiveQuery class for [[XpMemberGroup]].
 *
 * @see XpMemberGroup
 */
class XpMemberGroupQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XpMemberGroup[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XpMemberGroup|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
