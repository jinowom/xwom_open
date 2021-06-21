<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\config\ConfigSysinfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Config Sysinfos');

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
                        <div class="config-sysinfo-index">

                            <p>
                                <?= Html::a(Yii::t('app', 'Create Config Sysinfo'), ['create'], ['class' => 'btn btn-success']) ?>
                            </p>

                            <?php Pjax::begin(); ?>
                                                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                        
                                                    <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
        'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],

                                    'siteid',
            'name',
            'dirname',
            'domain',
            'serveralias',
            //'keywords',
            //'description',
            //'site_point',
            //'smarty_id',
            //'smarty_app_id',
            //'address:ntext',
            //'zipcode',
            //'tel',
            //'fax',
            //'email:email',
            //'copyright',
            //'logo:ntext',
            //'banner:ntext',
            //'reg_time',
            //'begin_time',
            //'end_time',
            //'basemail:email',
            //'mailpwd',
            //'record',
            //'created_at',
            //'updated_at',
            //'default_style',
            //'contacts',
            //'comp_invoice',
            //'comp_bank',
            //'bank_numb',

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


