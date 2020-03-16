<?php

namespace backend\modules\xpaper\models;

/**
 * This is the ActiveQuery class for [[XpMember]].
 *
 * @see XpMember
 */
class XpMemberQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XpMember[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XpMember|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
