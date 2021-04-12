<?php
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View 
 * @var common\models\config\ConfigBaseSearch $searchModel
* @var $dataProvider yii\data\ActiveDataProvider 
*/

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\config\ConfigBaseSearch $searchModel
 */

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'title',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'name',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'app_id',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'type',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'extra',
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'description',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'is_hide_des',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'default_value',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'sort',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'status',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'created_at',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'updated_at',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'created_id',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'updated_id',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'headerOptions' => [
                'width' => '15%'
        ],
        'template' =>'<div class="layui-table-cell"> {view} {update} {delete} </div>',
        'buttons' => [
                'view' => function ($url, $model, $key){
                    //return Html::a('查看', Url::to(['view','id'=>$model->id]), ['class' => "layui-btn layui-btn-xs layui-default-view"]);
                    return Html::Button('查看',
                            [
                            'onclick' => 'xadmin.open("查看", "'.$url.'",500,550)',
                            'data-target' => '#view-modal',
                            'class' => 'layui-btn layui-btn-xs layui-default-view',
                            'id' => 'modalButton',
                            ]
                        ); 

                },
                'update' => function ($url, $model, $key) {
                    //重新赋值 $url
                    //$url = Yii::$app->urlManager->createUrl(['config-base/view', 'id' => $model->id, 'edit' => 't']);
                    return Html::Button('修改',
                            [
                            'onclick' => 'xadmin.open("修改", "'.$url.'",500,550)',
                            'data-target' => '#update-modal',
                            'class' => 'layui-btn layui-btn-normal layui-btn-xs layui-default-update',
                            'id' => 'modalButton',
                            ]
                        );  

                },

                'delete'=>function($url,$model,$key)
                    {
                        $options=[
                            'title'=>Yii::t('app', 'Delete'),
                            'aria-label'=>Yii::t('app','delete'),
                            'data-confirm'=>Yii::t('app','Are you sure you want to delete this item?'),
                            'data-method'=>'post',
                            'data-pjax'=>'0',
                            'class'=>'layui-btn layui-btn-danger layui-btn-xs layui-default-delete'
                            ];
                            return Html::a('删除',$url,$options); 
                        //if($model->status == 0){//不同的视图，需要修改这里字段名称 或者不用判断，直接 return
                            //return Html::a('删除',$url,$options); 
                            //} else {
                             //  return Html::a('已审',$url,$options);  
                           // }
                    },
        ],

    ],

];   