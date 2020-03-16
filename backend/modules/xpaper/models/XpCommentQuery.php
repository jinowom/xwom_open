<?php

namespace backend\modules\xpaper\models;

/**
 * This is the ActiveQuery class for [[XpComment]].
 *
 * @see XpComment
 */
class XpCommentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XpComment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XpComment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
