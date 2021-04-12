<?php

use yii\helpers\Html;
use backend\widgets\DetailView;
use yii\helpers\Url;
use backend\assets\AppAsset;
AppAsset::register($this);
/* @var $this yii\web\View */
/* @var $model common\models\config\ConfigBehavior */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Config Behaviors'), 'url' => ['index']];

\yii\web\YiiAsset::register($this);
?>
<div class="layui-card-body">
    <div class="view config-behavior-view">
            <div class="layui-fluid layui-card" style="padding: 30px 30px;">
            <div class="layui-row">
                <!--<h3><?= Html::encode($this->title) ?></h3>-->
            <p>
                <?= Html::a(Yii::t('app', 'Rupdate'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
            <?= DetailView::widget([
                'model' => $model,
                        'options' => ['class' => 'layui-table'],
                        'template' => '<tr><th width="100px">{label}</th><td>{value}</td></tr>', 
                'attributes' => [
                    'id',
            'app_id',
            'url:url',
            'method',
            'behavior',
            'action',
            'level',
            'is_record_post',
            'is_ajax',
            'remark',
            'addons_name',
            'is_addon',
            'status',
            'created_at',
            'updated_at',
            'created_id',
            'updated_id',
                ],
            ]) ?>
            <p>
                <?= Html::a(Yii::t('app', 'Rupdate'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
            </div>
       </div>
    </div>
</div>
