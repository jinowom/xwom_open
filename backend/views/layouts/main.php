<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!doctype html>
<html class="x-admin-sm" lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <title><?=Html::encode($this->title)?></title>
    <?php
    $this->registerMetaTag(["name" => "renderer", "content" => "webkit|ie-comp|ie-stand"]);
    $this->registerMetaTag(["http-equiv" => "X-UA-Compatible", "content" => "IE=edge,chrome=1"]);
    $this->registerMetaTag(["name" => "viewport", "content" => "width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"]);
    $this->registerMetaTag(["http-equiv" => "Cache-Control", "content" => "no-siteapp"]);
    ?>
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php $this->head() ?>
</head>
<body class="index">
<?php $this->beginBody() ?>
<?php echo $content;?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
