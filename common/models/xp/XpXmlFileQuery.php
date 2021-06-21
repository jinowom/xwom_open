<?php

namespace common\models\xp;

/**
 * This is the ActiveQuery class for [[XpXmlFile]].
 *
 * @see XpXmlFile
 */
class XpXmlFileQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return XpXmlFile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return XpXmlFile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
