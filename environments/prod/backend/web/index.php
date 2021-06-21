<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);//开发时使用
defined('YII_ENV') or define('YII_ENV', 'dev');//开发时使用
//defined('YII_DEBUG') or define('YII_DEBUG', false);//生产交付时使用
//defined('YII_ENV') or define('YII_ENV', 'prod');//生产交付时使用

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';
require __DIR__ . '/../../common/helpers/function.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php',
    require __DIR__ . '/../config/main.php',
    require __DIR__ . '/../config/main-local.php'
);

(new yii\web\Application($config))->run();
