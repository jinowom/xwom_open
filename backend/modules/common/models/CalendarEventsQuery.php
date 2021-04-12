<?php

namespace backend\modules\common\models;

/**
 * This is the ActiveQuery class for [[CalendarEvents]].
 *
 * @see CalendarEvents
 */
class CalendarEventsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return CalendarEvents[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return CalendarEvents|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
