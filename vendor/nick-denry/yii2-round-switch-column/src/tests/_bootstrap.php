<?php

define('YII_ENABLE_ERROR_HANDLER', false);
define('YII_DEBUG', true);

defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', __DIR__.'/../../../../..');

require_once(__DIR__ . '/../../../../autoload.php');
require_once(__DIR__ . '/../../../../yiisoft/yii2/Yii.php');
require_once YII_APP_BASE_PATH . '/common/config/bootstrap.php';
require_once YII_APP_BASE_PATH . '/backend/config/bootstrap.php';
