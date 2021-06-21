<?php

use yii\helpers\Html;
use backend\widgets\DetailView;
use yii\helpers\Url;
use backend\assets\AppAsset;
AppAsset::register($this);
/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\WomPlan */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Wom Plans'), 'url' => ['index']];

\yii\web\YiiAsset::register($this);
?>
<div class="layui-card-body">
    <div class="view wom-plan-view">
            <div class="layui-fluid layui-card" style="padding: 30px 30px;">
            <div class="layui-row">
                <!--<h3><?= Html::encode($this->title) ?></h3>-->
            <?= DetailView::widget([
                'model' => $model,
                        'options' => ['class' => 'layui-table'],
                        'template' => '<tr><th width="100px">{label}</th><td>{value}</td></tr>', 
                'attributes' => [
                    'id',
            'title',
            'desc',
            [
                'attribute'=>'status',
                'label'=>'日程状态',
                'value'=> function($model){
                    return isset($model->status0)?$model->status0->name:'';
                }
            ],        
            [
                'attribute'=>'time_status',
                'label'=>'事件状态',
                'value'=> function($model){
                    return isset($model->timeStatus)?$model->timeStatus->name:'';
                }
            ],                   
            [
            'attribute'=>'admin_id',
            'label'=>'处理人',
            'value' => function($model){
                return isset($model->userInfo)?$model->userInfo->real_name:'';
            }	
            ],
            
            'start_at:datetime',
            'end_at:datetime',
            'created_at:datetime',
            [
            'attribute'=>'created_id',
            'label'=>'创建人',
            'value' => function($model){
                return isset($model->createdInfo)?$model->createdInfo->real_name:'';
            }	
            ],
            'updated_at:datetime',        
            [
            'attribute'=>'updated_id',
            'label'=>'最后修改人',
            'value' => function($model){
                return isset($model->updatedInfo)?$model->userInfo->real_name:'';
            }	
            ],
                ],
            ]) ?>

            </div>
       </div>
    </div>
</div>
