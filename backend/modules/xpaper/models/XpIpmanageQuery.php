<?php

namespace backend\modules\xpaper\models;

/**
 * This is the ActiveQuery class for [[XpIpmanage]].
 *
 * @see XpIpmanage
 */
class XpIpmanageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XpIpmanage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XpIpmanage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
