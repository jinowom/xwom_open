<?php

namespace backend\modules\xpaper\models;

/**
 * This is the ActiveQuery class for [[XpPaper]].
 *
 * @see XpPaper
 */
class XpPaperQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XpPaper[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XpPaper|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
