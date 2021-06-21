<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\config\ConfigBehaviorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Config Behaviors');

$pageSize = \Yii::$app->params['pageSize'];
$limitsJson = \Yii::$app->params['limitsJson'];
?>
<!-- 面包屑 -->
<?= \Yii::$app->view->renderFile('@app/views/public/breadcrumb.php')?>
<!-- 面包屑 -->
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">

                <div class="layui-card-body ">
                <div class = "layui-btn-container layui-table-tool" >
                    <?= Html::a(Yii::t('app', '<i class= layui-icon></i>添加 Config Behavior'), ['create'], ['class' => 'layui-btn']) ?>
                    <button class="layui-btn layui-btn-sm" lay-event="refresh">刷新</button >
                </div >
                <div class="layui-card-body  config-behavior-index">
                <table class="layui-table" id="adminList" lay-filter="adminList">
                        <?php Pjax::begin(); ?>
                                            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                    
                                            <?= GridView::widget([
                            'dataProvider' => $dataProvider,//数据提供者
                            //'showFooter'=>true,//显示底部（就是多了一栏），默认是关闭的
                            //'summary' => "{begin}-{end}-{count}-{totalCount}-{page}-{pageCount}",//数据的相关信息，行，页面，总数等
                            //'summaryOptions' => ['class' => 'summarys'],
                            'filterModel' => $searchModel,
             'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                            'id',
            'app_id',
            'url:url',
            'method',
            'behavior',
            //'action',
            //'level',
            //'is_record_post',
            //'is_ajax',
            //'remark',
            //'addons_name',
            //'is_addon',
            //'status',
            //'created_at',
            //'updated_at',

                                ['class' => 'yii\grid\ActionColumn',
                                    "header" => "操作", 
                                    'headerOptions' => ['width' => 'auto'],
                                    'template' => '{view}{update}{delete}',//{approve}
                                    'buttons' =>[
                                        'approve' => function ($url, $model, $key)
                                        {
                                            $options = [
                                                'title' => Yii::t('app', '审核'),
                                                'arid-label' => Yii::t('app', '审核'),
                                               // 'data-confirm' => Yii::t('app', '你确定要通过这条评论吗？'),
                                                'data-method' => 'post',
                                                'data-ajax' => '0',
                                            ];
                                            return Html::a('<span class="glyphicon glyphicon-check"></span>', $url, $options);
                                        }
                                    ],
                                    'footerOptions'=>['class'=>'hide'],
                                ],
                            ],
                        ]); ?>
                    
                        <?php Pjax::end(); ?>

                    
                </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



