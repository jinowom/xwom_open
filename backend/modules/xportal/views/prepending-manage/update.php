<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\assets\AppAsset;
AppAsset::register($this);

/* @var $this yii\web\View */
/* @var $model backend\modules\xportal\models\XportalPushData */

$this->title = Yii::t('app', 'Update Xportal Push Data: {name}', [
    'name' => $model->push_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Xportal Push Datas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->push_id, 'url' => ['view', 'id' => $model->push_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="layui-card-body">
    <div class="update xportal-push-data-update">
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