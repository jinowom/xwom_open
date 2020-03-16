<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="layui-container">
<div class="fly-panel"> 
<div class="fly-none">
 <h2><i class="layui-icon layui-icon-404"></i></h2>
 <p><?=$exception->getMessage()?></p>
</div>
</div>
</div>