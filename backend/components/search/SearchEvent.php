<?php
/**
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-07 11:39 
 */
namespace backend\components\search;

class SearchEvent extends \yii\base\Event
{
    const BEFORE_SEARCH = 1;

    /** @var $query \yii\db\ActiveQuery */
    public $query;
}