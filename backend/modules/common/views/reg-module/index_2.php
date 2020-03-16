<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\reg\RegModuleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Reg Modules');
$this->params['breadcrumbs'][] = $this->title;
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
                    <?= Html::a(Yii::t('app', '<i class= layui-icon></i>添加 Reg Module'), ['create'], ['class' => 'layui-btn']) ?>
                    <button class="layui-btn layui-btn-sm" lay-event="refresh">刷新</button >
                </div >
                <div class="layui-card-body  reg-module-index">
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
            'title',
            'name',
            'title_initial',
            'bootstrap',
            //'service',
            //'cover',
            //'brief_introduction',
            //'description',
            //'author',
            //'version',
            //'is_setting',
            //'is_rule',
            //'is_merchant_route_map',
            //'default_config',
            //'console',
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
                                                'title' => Yii::t('yii', '审核'),
                                                'arid-label' => Yii::t('yii', '审核'),
                                               // 'data-confirm' => Yii::t('yii', '你确定要通过这条评论吗？'),
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



