<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\xportal\models\XportalPushData */

$this->title = Yii::t('app', 'Create Xportal Push Data');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Xportal Push Datas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="layui-card-body">
    <div class="create xportal-push-data-create">
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
