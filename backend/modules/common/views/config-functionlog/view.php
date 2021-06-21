<?php

use kartik\detail\DetailView;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model common\models\log\ConfigFunctionlog */

$this->title = Yii::t('app', $model->id);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Config Functionlogs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="layui-card-body">
    <div class="view config-functionlog-view">
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
            'app_id',
            'merchant_id',
            'user_id',
            'method',
            'module',
            'controller',
            'action',
            'url:url',
            'get_data:ntext',
            'post_data:ntext',
            'header_data:ntext',
            'ip',
            'error_code',
            'error_msg',
            'error_data:ntext',
            'req_id',
            'user_agent',
            'device',
            'device_uuid',
            'device_version',
            'device_app_version',
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

            </div>
       </div>
    </div>
</div>

