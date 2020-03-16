<?php

namespace backend\modules\common\models;

/**
 * This is the ActiveQuery class for [[WomPlantimeStatus]].
 *
 * @see WomPlantimeStatus
 */
class WomPlantimeStatusQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return WomPlantimeStatus[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return WomPlantimeStatus|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
