<?php

namespace services\backend;

use Yii;
use common\enums\AppEnum;
use common\components\Service;

/**
 * Class BackendService
 * @package services\backend
 * @author Womtech  email:chareler@163.com
 */
class BackendService extends Service
{
    /**
     * @return int
     * @throws \yii\db\Exception
     */
    public function getDefaultDbSize()
    {
        $db = Yii::$app->db;
        $models = $db->createCommand('SHOW TABLE STATUS')->queryAll();
        $models = array_map('array_change_key_case', $models);
        // 数据库大小
        $mysqlSize = 0;
        foreach ($models as $model) {
            $mysqlSize += $model['data_length'];
        }

        return $mysqlSize;
    }


}