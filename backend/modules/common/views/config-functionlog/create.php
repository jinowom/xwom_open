<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\log\ConfigFunctionlog */

$this->title = Yii::t('app', 'Create Config Functionlog');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Config Functionlogs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="layui-card-body">
    <div class="create config-functionlog-create">
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
