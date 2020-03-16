<?php

namespace backend\modules\common\models;

/**
 * This is the ActiveQuery class for [[WomPlan]].
 *
 * @see WomPlan
 */
class WomPlanQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return WomPlan[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return WomPlan|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
