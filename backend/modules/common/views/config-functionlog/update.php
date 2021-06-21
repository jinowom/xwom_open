<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\log\ConfigFunctionlog */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Config Functionlog',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Config Functionlogs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

?>


<div class="layui-card-body">
    <div class="update config-functionlog-update">
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
