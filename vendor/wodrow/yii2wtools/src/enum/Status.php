<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-11-4
 * Time: 下午3:20
 */

namespace wodrow\yii2wtools\enum;


class Status
{
    const STATUS_ACTIVE = 10;
    const STATUS_DEL = -10;

    public static function getStatus()
    {
        return [
            self::STATUS_ACTIVE => "正常",
            self::STATUS_DEL => "删除",
        ];
    }
}