<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\config\ConfigSearchengine */

$this->title = Yii::t('app', 'Create Config Searchengine');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Config Searchengines'), 'url' => ['index']];

?>
<div class="layui-card-body">
    <div class="create config-searchengine-create">
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
