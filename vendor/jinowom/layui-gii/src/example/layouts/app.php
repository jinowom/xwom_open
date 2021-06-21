<?php
/**
 * Created by PhpStorm.
 */
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="utf-8">
    <title>后台管理</title>
    <meta name="renderer" content="webkit">
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="X-CSRF-TOKEN" content="{{csrf_token()}}">
    <link rel="stylesheet" href="/layui/css/layui.css" media="all" />
    <link rel="stylesheet" href="/css/public.css" media="all" />

    <script type="text/javascript" src="/layui/layui.js"></script>
    <script type="text/javascript" src="/js/public.js"></script>

</head>
<body class="childrenBody">
    <?= $content ?>
</div>
</body>
</html>

