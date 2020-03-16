<?php

namespace backend\modules\xpaper\models;

/**
 * This is the ActiveQuery class for [[XpSensitiveWord]].
 *
 * @see XpSensitiveWord
 */
class XpSensitiveWordQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XpSensitiveWord[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XpSensitiveWord|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
