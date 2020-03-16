<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\reg\RegSoftware */

$this->title = Yii::t('app', 'Create Reg Software');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reg Softwares'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="layui-card-body">
    <div class="create reg-software-create">
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
