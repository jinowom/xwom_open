<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\assets\AppAsset;
AppAsset::register($this);

/* @var $this yii\web\View */
/* @var $model backend\modules\xportal\models\XportalTheme */

$this->title = Yii::t('app', 'Update Xportal Theme: {name}', [
    'name' => $model->theme_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Xportal Themes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->theme_id, 'url' => ['view', 'id' => $model->theme_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="layui-card-body">
    <div class="update xportal-theme-update">
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