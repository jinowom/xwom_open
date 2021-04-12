<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\log\ConfigSmslog */

$this->title = Yii::t('app', 'Create Config Smslog');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Config Smslogs'), 'url' => ['index']];

?>
<div class="layui-card-body">
    <div class="create config-smslog-create">
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
