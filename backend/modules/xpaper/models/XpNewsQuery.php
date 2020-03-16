<?php

namespace backend\modules\xpaper\models;

/**
 * This is the ActiveQuery class for [[XpNews]].
 *
 * @see XpNews
 */
class XpNewsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XpNews[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XpNews|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
