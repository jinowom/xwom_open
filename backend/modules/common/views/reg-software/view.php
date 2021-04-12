<?php

use kartik\detail\DetailView;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model common\models\reg\RegSoftware */

$this->title = Yii::t('app', $model->title);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reg Softwares'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="layui-card-body">
    <div class="view reg-software-view">
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
            'title_initial',
            'bootstrap',
            'service',
            'cover',
            'brief_introduction',
            'description',
            'author',
            'version',
            'is_setting',
            'is_rule',
            'parent_rule_name',
            'route_map',
            'default_config:ntext',
            'console:ntext',
            'status',
            'created_at',
            'updated_at',
            'created_id',
            'updated_id',
            'sortOrder',
            'is_del',
        ],
    ]) ?>

            </div>
       </div>
    </div>
</div>

