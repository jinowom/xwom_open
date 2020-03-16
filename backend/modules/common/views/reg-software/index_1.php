<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\reg\RegSoftwareSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Reg Softwares');
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
                <div class="layui-card-body">
                    <form class="layui-form layui-col-space5">
                        <div class="layui-inline layui-show-xs-block">
                            <input class="layui-input" autocomplete="off" placeholder="开始日" name="start" id="start"></div>
                        <div class="layui-inline layui-show-xs-block">
                            <input class="layui-input" autocomplete="off" placeholder="截止日" name="end" id="end"></div>
                        <div class="layui-inline layui-show-xs-block">
                            <button class="layui-btn" type="button" lay-submit="false" onclick="search()" lay-filter="sreach">
                                <i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>
                <div class="layui-card-body ">
                    <table class="layui-table" id="adminList" lay-filter="adminList">
                        <div class="reg-software-index">

                            <p>
                                <?= Html::a(Yii::t('app', 'Create Reg Software'), ['create'], ['class' => 'btn btn-success']) ?>
                            </p>

                            <?php Pjax::begin(); ?>
                                                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                        
                                                    <?= GridView::widget([
                                'dataProvider' => $dataProvider,
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

                                    ['class' => 'yii\grid\ActionColumn'],
                                ],
                            ]); ?>
                        
                            <?php Pjax::end(); ?>

                        </div>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


