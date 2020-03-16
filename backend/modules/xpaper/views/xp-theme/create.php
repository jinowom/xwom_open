<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\xpaper\models\XpTheme */

$this->title = Yii::t('app', 'Create Xp Theme');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Xp Themes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="layui-card-body">
    <div class="create xp-theme-create">
            <div class="layui-fluid layui-card" style="padding: 30px 30px;">
            <div class="layui-row">
        <!--<h3><?= Html::encode($this->title) ?></h3>-->
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

            </div>
       </div>
    </div>
</div>
