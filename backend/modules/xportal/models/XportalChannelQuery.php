<?php

namespace backend\modules\xportal\models;

/**
 * This is the ActiveQuery class for [[XportalChannel]].
 *
 * @see XportalChannel
 */
class XportalChannelQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XportalChannel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XportalChannel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
