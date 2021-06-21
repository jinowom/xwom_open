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
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>">
    <meta http-equiv="Cache-Control"content="no-store" />
    <meta http-equiv=〞Pragma〞,content=〞no-cache〞>
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta http-equiv="Content-Security-Policy" content="child-src http: https:" />
    <meta http-equiv="Strict-Transport-Security" content="max-age=31536000;includeSubDomains" />
    <meta http-equiv="X-Content-Type-Options" content="nosniff" />
    <meta http-equiv="X-XSS-Protection" content="1; mode=block" />
    <meta http-equiv="Expires" content="0" />
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
    <?php $this->registerCsrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<?php $this->beginBody() ?>
<?php echo $content?>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
