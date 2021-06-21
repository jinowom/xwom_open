<?php

use kartik\detail\DetailView;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model common\models\config\ConfigBase */

$this->title = Yii::t('app', $model->title);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Config Bases'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="layui-card-body">
    <div class="view config-base-view">
            <div class="layui-fluid layui-card" style="padding: 30px 30px;">
            <div class="layui-row">
            <!--<h3><?= Html::encode($this->title) ?></h3>-->
 
    <?= DetailView::widget([
        'model' => $model,
        'condensed' => true,
        'hover' => true,
        'enableEditMode' => false,
        'panel' => [
            'heading' => "详细",
            'type' => DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'id',
            'title',
            'name',
            'app_id',
            'type',
            'extra',
            'description',
            'is_hide_des',
            'default_value',
            'sort',
            'status',
            'created_at',
            'updated_at',
            'created_id',
            'updated_id',
        ],
    ]) ?>

            </div>
       </div>
    </div>
</div>

