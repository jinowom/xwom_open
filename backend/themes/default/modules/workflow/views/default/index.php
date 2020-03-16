<?php
/*
 * @var yii\web\View $this
 */

use jinostart\workflow\manager\models\Workflow;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\grid\GridView;

//$this->title = Yii::t('workflow', 'Workflow');
$this->title = Yii::t('workflow', '工作流列表');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '系统管理'), 'url' => ['/system-index/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- Begin: Content -->
<section id="content">
    <!-- full width widgets -->

    <div class="row">
        <div class="col-lg-12">
            <div class="panel">
                <div class="panel-body">
                    <p>
                        <?= Html::a(Yii::t('app', '新增工作流'), ['create'], ['class' => 'btn btn-success']) ?>
                    </p>
                    <?php
                    //            $items = [
                    //                [
                    //                    'label' => '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('workflow', 'Create'),
                    //                    'url' => ['create'],
                    //                    'encode' => false,
                    //                ],
                    //            ];
                    //            echo Nav::widget([
                    //                'items' => $items,
                    //                'options' => ['class' => 'nav-pills'],
                    //            ]);
                    //


                    $dataProvider = new \yii\data\ArrayDataProvider([
                        'allModels' => Workflow::find()->orderBy(['id' => SORT_ASC])->all(),
                        'key' => 'id'
                    ])
                    ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'tableOptions' => [
                            'class' => 'footable table table-stripped toggle-arrow-tiny'
                        ],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'id',
                            'name',
                            'name_cn',
                            ['class' => 'yii\grid\ActionColumn',
                                'template' => '{view}{update}'],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
