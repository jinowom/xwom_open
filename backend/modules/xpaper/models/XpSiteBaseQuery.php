<?php

namespace backend\modules\xpaper\models;

/**
 * This is the ActiveQuery class for [[XpSiteBase]].
 *
 * @see XpSiteBase
 */
class XpSiteBaseQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XpSiteBase[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XpSiteBase|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
