<?php

namespace common\models\xp;

/**
 * This is the ActiveQuery class for [[XpDataXml]].
 *
 * @see XpDataXml
 */
class XpDataXmlQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XpDataXml[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XpDataXml|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
