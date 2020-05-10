<?php

defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', __DIR__.'/../../../../../..');

return yii\helpers\ArrayHelper::merge(
    require YII_APP_BASE_PATH . '/common/config/test-local.php',
    require YII_APP_BASE_PATH . '/backend/config/main.php',
    require YII_APP_BASE_PATH . '/backend/config/main-local.php',
    #require YII_APP_BASE_PATH . '/backend/config/test.php',
    [
    ]
);
