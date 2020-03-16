<?php

namespace common\models\reg;

/**
 * This is the ActiveQuery class for [[RegWidgets]].
 *
 * @see RegWidgets
 */
class RegWidgetsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RegWidgets[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RegWidgets|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
