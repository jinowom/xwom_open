<?php

namespace common\models\reg;

/**
 * This is the ActiveQuery class for [[RegExtension]].
 *
 * @see RegExtension
 */
class RegExtensionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RegExtension[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RegExtension|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
