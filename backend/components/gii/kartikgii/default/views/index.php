<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;


/**
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";


?>
/**
 * This is the view;
 * @author  Womtech  email:chareler@163.com
 * @DateTime <?= date("Y-m-d H:i",time()) ?><?php echo "\n";?>
 */
 
use yii\helpers\Html;
use yii\helpers\Url;
use <?= $generator->indexWidgetType === 'grid' ? "kartik\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
use yii\widgets\Pjax;
use backend\assets\AppAsset;
AppAsset::register($this); <?php echo "\n";?>

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
<?= !empty($generator->searchModelClass) ? " * @var " . ltrim($generator->searchModelClass, '\\') . " \$searchModel\n" : '' ?>
 */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Xp Specials'), 'url' => ['/xpaper/xp-special/index']];//上级菜单示例
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- 面包屑 -->
<?php  echo "<?";?>
= \Yii::$app->view->renderFile('@app/views/public/breadcrumb.php')<?php echo "?>\n";?>
<!-- 面包屑 -->
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
          <div class="layui-card-body layui-table-body layui-table-main">
                    
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">

<?php if (!empty($generator->searchModelClass)): ?>
<?= "    <?php " . ($generator->indexWidgetType === 'grid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
<?php endif; ?>

    <p>
        <?= "<?php /* echo " ?>Html::a(<?= $generator->generateString('Create {modelClass}', ['modelClass' => Inflector::camel2words(StringHelper::basename($generator->modelClass))]) ?>, ['create'], ['class' => 'btn btn-success'])<?= "*/ " ?> ?>
    </p>

<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?= "<?php Pjax::begin(); echo " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'layui-table-box ','style'=>'overflow:auto', 'id' => 'grid'],
        //'layout'=> '{items}<div class="layui-table-page"><div id="layui-table-page1"><div class="layui-box layui-laypage layui-laypage-default" id="layui-laypage-1">{pager}</div></div></div>',
        'layout'=> '{items}<div style="margin: 10px 0 0 10px;">{pager}</div>',
        'tableOptions'=> ['class'=>'layui-table','style'=>'width: 100%; '],
        'pager' => [
                //'options'=>['class'=>'hidden'],//关闭自带分页
                'options'=>['class'=>'layuipage pull-left'],
                        'prevPageLabel' => '上一页',
                        'nextPageLabel' => '下一页    ',
                        'firstPageLabel'=>'首页    ',
                        'lastPageLabel'=>'尾页',
                        'maxButtonCount'=>5,
        ],
        //GridView控制行样式 rowOptions属性
        //'showFooter'=>true,//显示底部（就是多了一栏），默认是关闭的
        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>
            //['class' => 'yii\grid\SerialColumn'],
            [
                    'class' => 'backend\grid\CheckboxColumn',
                    'checkboxOptions' => ['lay-skin'=>'primary','lay-filter'=>'choose'],
                    'headerOptions' => ['width'=>'20','style'=> 'text-align: center;'],
                    'contentOptions' => ['style'=> 'text-align: center;']
            ],
<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo "            '" . $name . "',\n";
        } else {
            echo "            // '" . $name . "',\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        if ($column->type === 'date') {
            $columnDisplay = "            ['attribute' => '$column->name','format' => ['date',(isset(Yii::\$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::\$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],";

        } elseif ($column->type === 'time') {
            $columnDisplay = "            ['attribute' => '$column->name','format' => ['time',(isset(Yii::\$app->modules['datecontrol']['displaySettings']['time'])) ? Yii::\$app->modules['datecontrol']['displaySettings']['time'] : 'H:i:s A']],";
        } elseif ($column->type === 'datetime' || $column->type === 'timestamp') {
            $columnDisplay = "            ['attribute' => '$column->name','format' => ['datetime',(isset(Yii::\$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::\$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],";
        } else {
            $columnDisplay = "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',";
        }
        if (++$count < 6) {
            echo $columnDisplay ."\n";
        } else {
            echo "//" . $columnDisplay . " \n";
        }
    }
}
?>
            [
            'header' => '<div class="layui-table-cell">操作</div>',
				'class' => 'yii\grid\ActionColumn',
				'headerOptions' => [
					'width' => '15%'
				],
                                'template' =>'<div class="layui-table-cell"> {view} {update} {delete} </div>',
				'buttons' => [
                                        'view' => function ($url, $model, $key){
                                            //return Html::a('查看', Url::to(['view','id'=>$model->id]), ['class' => "layui-btn layui-btn-xs layui-default-view"]);
                                            return Html::Button(Yii::t('app', 'View'),
                                                    [
                                                    'onclick' => 'xadmin.open("查看", "'.$url.'",500,550)',
                                                    'data-target' => '#view-modal',
                                                    'class' => 'layui-btn layui-btn-xs layui-default-view',
                                                    'id' => 'modalButton',
                                                    ]
                                                ); 
                                                    
                                        },
                                        'update' => function ($url, $model, $key) {
                                            //return Html::a('修改', Url::to(['update','id'=>$model->id]), ['class' => "layui-btn layui-btn-normal layui-btn-xs layui-default-update"]);
                                            //重新赋值 $url
                                            $url = Yii::$app->urlManager->createUrl(['<?= (empty($generator->moduleID) ? '' : $generator->moduleID . '/') . $generator->controllerID?>/view', <?= $urlParams ?>, 'edit' => 't']);
                                            return Html::Button(Yii::t('app', 'Update'),
                                                    [
                                                    'onclick' => 'xadmin.open("'.Yii::t('app', 'Update').'", "'.$url.'",500,550)',
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
                                                    return Html::a(Yii::t('app','Delete'),$url,$options); 
                                                //if($model->status == 0){//不同的视图，需要修改这里字段名称 或者不用判断，直接 return
                                                    //return Html::a('删除',$url,$options); 
                                                    //} else {
                                                     //  return Html::a('已审',$url,$options);  
                                                   // }
                                            },
				]
            ],
            /**下面的yii自带的样式，如果不需要，必须删除**/
            /*
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            Yii::$app->urlManager->createUrl(['<?= (empty($generator->moduleID) ? '' : $generator->moduleID . '/') . $generator->controllerID?>/view', <?= $urlParams ?>, 'edit' => 't']),
                            ['title' => Yii::t('app', 'Edit'),]
                        );
                    }
                ],
            ],
            **
            */
        ],
        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => true,

        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type' => 'info',
            'before' => Html::a('<i class="glyphicon glyphicon-plus"></i>  ' . Yii::t('app', 'Create new'), ['create'], ['class' => 'btn btn-success']),
            'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i>  ' . Yii::t('app', 'Reset Grid'), ['index'], ['class' => 'btn btn-info']),
            'showFooter' => false
        ],
    ]); Pjax::end(); ?>
<?php else: ?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]) ?>
<?php endif; ?>

</div>
                
                 </div>
            
            </div>
        </div>
    </div>
</div> 