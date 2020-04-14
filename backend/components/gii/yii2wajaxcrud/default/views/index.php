<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $generator \wodrow\wajaxcrud\generators\crud\Generator */
$modelClass = StringHelper::basename($generator->modelClass);
$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$actionParams = $generator->generateActionParams();

$editableFields = $generator->generateEditableFields();
$dateRangeFields = $generator->generateDateRangeFields();
$thumbImageFields = $generator->generateThumbImageFields();
$roundSwitchFields = $generator->generateRoundSwitchFields();
$statusFields = $generator->statusFields;

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>
/**
 * This is the view;
 * @author  Womtech  email:chareler@163.com
 * @DateTime <?= date("Y-m-d H:i",time()) ?><?php echo "\n";?>
 */
use kartik\grid\GridView;
use kartik\grid\DataColumn;
use kartik\grid\SerialColumn;
use kartik\grid\EditableColumn;
use kartik\grid\CheckboxColumn;
use kartik\grid\ExpandRowColumn;
use kartik\grid\EnumColumn;
use kartik\grid\ActionColumn;
use kartik\daterange\DateRangePicker;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use wodrow\wajaxcrud\CrudAsset;
use wodrow\wajaxcrud\BulkButtonWidget;
use wodrow\yii2wtools\enum\Status;
use wodrow\yii2wtools\tools\JsBlock;
use nickdenry\grid\toggle\components\RoundSwitchColumn;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Xp Specials'), 'url' => ['/xpaper/xp-special/index']];//上级菜单示例
$this->params['breadcrumbs'][] = $this->title;
CrudAsset::register($this);

?>
<!-- 面包屑 -->
<?php  echo "<?";?>
= \Yii::$app->view->renderFile('@app/views/public/breadcrumb.php')<?php echo "?>\n";?>
<!-- 面包屑 -->
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
    <div id="ajaxCrudDatatable">
        <?="<?= "?>GridView::widget([
            'id' => 'crud-datatable',
            'rowOptions' => [
                'class' => 'gvRowBaguetteBox',
            ],
            'dataProvider' => $dataProvider,
            //重新定义分页样式开始
            'pager' => [
                    //'options'=>['class'=>'hidden'],//关闭自带分页
                    'options'=>['class'=>'layuipage pull-left'],
                            'prevPageLabel' => '上一页',
                            'nextPageLabel' => '下一页    ',
                            'firstPageLabel'=>'首页    ',
                            'lastPageLabel'=>'尾页',
                            'maxButtonCount'=>5,
            ],
            //重新定义分页样式结束
            'filterModel' => $searchModel,
            'responsive' => true,
            'showPageSummary' => true,
            'pjax' => true,
            'hover' => true,
            'striped' => true,
            'condensed' => true,
            'columns' => [
                [
                    'class' => CheckboxColumn::class,
                    'width' => "20px",
                ],
                [
                    'class' => SerialColumn::class,
                    'width' => "40px",
                    'pageSummary' => "合计",
                ],
                [
                    'class' => ExpandRowColumn::class,
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'detail' => function ($model, $key, $index, $column) {
                        return $this->render('view', ['model' => $model]);
                    },
                    'expandOneOnly' => true,
                ],
                
                <?php
                $count = 0;
                foreach ($generator->getColumnNames() as $name) {
                     if ($name=='id') {
                            echo "           /*\n";
                            echo "           [\n";
                            echo "               'class' => DataColumn::class,\n";
                            echo "               'attribute' => '$name',\n";
                            echo "               'hAlign' => GridView::ALIGN_CENTER,\n";
                            echo "               'vAlign' => GridView::ALIGN_MIDDLE,\n";
                            echo "           ],\n";
                            echo "           */\n";
                        }
                    else if (++$count < 10) {
                        if (in_array($name, $editableFields)){
                            echo "           [\n";
                            echo "               'class' => EditableColumn::class,\n";
                            echo "               'attribute' => '$name',\n";
                            echo "               'hAlign' => GridView::ALIGN_CENTER,\n";
                            echo "               'vAlign' => GridView::ALIGN_MIDDLE,\n";
                            echo "               'readonly' => function (".'$model,$key,$index,$widget'.")  {\n";
                            echo "               return false;\n";
                            echo "               },\n";
                            echo "               'editableOptions' => function (".'$model,$key,$index,$widget'.")   {\n";
                            echo "               return [\n";
                            echo "               'header' => '修改',\n";
                            echo "               'size' => 'md',\n";
                            echo "               'formOptions' => ['action' => ['editable-edit']],\n";
                            echo "               ];\n";
                            echo "               },\n";
                            echo "               'refreshGrid' => true,\n";
                            echo "           ],\n";

                        } else if (in_array($name, $dateRangeFields)) {
                            echo "           [\n";
                            echo "               'class' => DataColumn::class,\n";
                            echo "               'attribute' => '$name',\n";
                            echo "               'hAlign' => GridView::ALIGN_CENTER,\n";
                            echo "               'vAlign' => GridView::ALIGN_MIDDLE,\n";
                            echo "               'format' => ['date', 'php:Y-m-d H:i'],\n";
                            echo "               'filter' => DateRangePicker::widget([\n";
                            echo "                   'model' => ".'$searchModel'.",\n";
                            echo "                   'attribute' => '$name',\n";
                            echo "                   'convertFormat' => true,\n";
                            echo "                   'pluginOptions' => [\n";
                            echo "                   'opens' => 'left',\n";
                            echo "                   'timePicker' => true,\n";
                            echo "                   'timePickerIncrement' => 30,\n";
                            echo "                   'locale' => [\n";
                            echo "                      'format' => 'Y-m-d H:i',\n";
                            echo "                      'cancelLabel' => '清除',\n";
                            echo "                   ],\n";
                            echo "                ],\n";
                            echo "                  //'useWithAddon' => true,\n";
                            echo "                  //'presetDropdown' => true,\n";
                            echo "                  'pjaxContainerId' => 'crud-datatable-pjax',\n";
                            echo "               ]),\n";
                            echo "           ],\n";
                        } else if (in_array($name, $thumbImageFields)) {
                            echo "           [\n";
                            echo "               'class' => DataColumn::class,\n";
                            echo "               'attribute' => '$name',\n";
                            echo "               'hAlign' => GridView::ALIGN_CENTER,\n";
                            echo "               'vAlign' => GridView::ALIGN_MIDDLE,\n";
                            echo "               'mergeHeader' => true,\n";
                            echo "               'enableSorting' => false,\n";
                            echo "               'format' => 'raw',\n";
                            echo "               'value' => function (".'$m'.") {\n";
                            echo "                      return Html::a(Html::img(".'$m'."->$name, ['alt' => '缩略图', 'width' => 120]), ".'$m'."->$name);\n";
                            echo "               },\n";
                            echo "           ],\n";
                        } else if (in_array($name, $roundSwitchFields)) {
                            echo "           [\n";
                            echo "               'class' => RoundSwitchColumn::class,\n";
                            echo "               'attribute' => '$name',\n";
                            echo "               /* other column options, i.e. */\n";
                            echo "               'headerOptions' => ['width' => 80],\n";
                            echo "               //'hAlign' => GridView::ALIGN_CENTER,\n";
                            echo "               //'vAlign' => GridView::ALIGN_MIDDLE,\n";
                            echo "           ],\n";
                        } else if ($name == $statusField) {
                            echo "           [\n";
                            echo "               'class' => EnumColumn::class,\n";
                            echo "               'attribute' => '$name',\n";
                            echo "               'hAlign' => GridView::ALIGN_CENTER,\n";
                            echo "               'vAlign' => GridView::ALIGN_MIDDLE,\n";
                            echo "               'enum' => Status::getStatus(),\n";
                            echo "           ],\n";
                        } else {
                            echo "           [\n";
                            echo "               'class' => DataColumn::class,\n";
                            echo "               'attribute' => '$name',\n";
                            echo "               'hAlign' => GridView::ALIGN_CENTER,\n";
                            echo "               'vAlign' => GridView::ALIGN_MIDDLE,\n";
                            echo "           ],\n";
                        }
                    } else {
                        if (in_array($name, $editableFields)){
                            
                            echo "           // [\n";
                            echo "            //    'class' => EditableColumn::class,\n";
                            echo "             //   'attribute' => '$name',\n";
                            echo "            //    'hAlign' => GridView::ALIGN_CENTER,\n";
                            echo "            //    'vAlign' => GridView::ALIGN_MIDDLE,\n";
                            echo "            //    'readonly' => function (".'$model,$key,$index,$widget'.")  {\n";
                            echo "            //    return false;\n";
                            echo "            //    },\n";
                            echo "           //     'editableOptions' => function (".'$model,$key,$index,$widget'.")   {\n";
                            echo "           //     return [\n";
                            echo "           //     'header' => '修改',\n";
                            echo "          //      'size' => 'md',\n";
                            echo "          //      'formOptions' => ['action' => ['editable-edit']],\n";
                            echo "           //     ];\n";
                            echo "          //      },\n";
                            echo "          //      'refreshGrid' => true,\n";
                            echo "         //   ],\n";
                        } else if (in_array($name, $dateRangeFields)) {
                            echo "             //  [\n";
                            echo "              //  'class' => DataColumn::class,\n";
                            echo "             //   'attribute' => '$name',\n";
                            echo "              //  'hAlign' => GridView::ALIGN_CENTER,\n";
                            echo "             //   'vAlign' => GridView::ALIGN_MIDDLE,\n";
                            echo "             //   'format' => ['date', 'php:Y-m-d H:i'],\n";
                            echo "              //  'filter' => DateRangePicker::widget([\n";
                            echo "                  //  'model' => ".'$searchModel'.",\n";
                            echo "                  //  'attribute' => '$name',\n";
                            echo "                  //  'convertFormat' => true,\n";
                            echo "                   // 'pluginOptions' => [\n";
                            echo "                  //  'opens' => 'left',\n";
                            echo "                   // 'timePicker' => true,\n";
                            echo "                  //  'timePickerIncrement' => 30,\n";
                            echo "                   // 'locale' => [\n";
                            echo "                      // 'format' => 'Y-m-d H:i',\n";
                            echo "                      // 'cancelLabel' => '清除',\n";
                            echo "                   // ],\n";
                            echo "                // ],\n";
                            echo "                  // //'useWithAddon' => true,\n";
                            echo "                 //  //'presetDropdown' => true,\n";
                            echo "                  // 'pjaxContainerId' => 'crud-datatable-pjax',\n";
                            echo "               // ]),\n";
                            echo "           // ],\n";
                        } else if (in_array($name, $thumbImageFields)) {
                            echo "           // [\n";
                            echo "               // 'class' => DataColumn::class,\n";
                            echo "               // 'attribute' => '$name',\n";
                            echo "               // 'hAlign' => GridView::ALIGN_CENTER,\n";
                            echo "               // 'vAlign' => GridView::ALIGN_MIDDLE,\n";
                            echo "               // 'mergeHeader' => true,\n";
                            echo "               // 'enableSorting' => false,\n";
                            echo "               // 'format' => 'raw',\n";
                            echo "               // 'value' => function ($m) {\n";
                            echo "                      // return Html::a(Html::img($m-><?=$name ?>, ['alt' => '缩略图', 'width' => 120]), $m-><?=$name ?>);\n";
                            echo "               // },\n";
                            echo "           // ],\n";
                        } else if (in_array($name, $roundSwitchFields)) {
                            echo "           [\n";
                            echo "               'class' => RoundSwitchColumn::class,\n";
                            echo "               'attribute' => '$name',\n";
                            echo "               /* other column options, i.e. */\n";
                            echo "               'headerOptions' => ['width' => 150],\n";
                            echo "               //'hAlign' => GridView::ALIGN_CENTER,\n";
                            echo "               //'vAlign' => GridView::ALIGN_MIDDLE,\n";
                            echo "           ],\n";
                        } else if ($name == $statusField) {
                            echo "           // [\n";
                            echo "               // 'class' => EnumColumn::class,\n";
                            echo "               // 'attribute' => '$name',\n";
                            echo "               // 'hAlign' => GridView::ALIGN_CENTER,\n";
                            echo "               // 'vAlign' => GridView::ALIGN_MIDDLE,\n";
                            echo "               // 'enum' => Status::getStatus(),\n";
                            echo "           // ],\n";
                        } else {
                            echo "           // [\n";
                            echo "               // 'class' => DataColumn::class,\n";
                            echo "               // 'attribute' => '$name',\n";
                            echo "               // 'hAlign' => GridView::ALIGN_CENTER,\n";
                            echo "               // 'vAlign' => GridView::ALIGN_MIDDLE,\n";
                            echo "           // ],\n";
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
                                                //重新赋值 $url //由于本grud模板，没有单独定义moduleID 所以下面重新赋值就失效了
                                                //$url = Yii::$app->urlManager->createUrl(['<?= (empty($generator->moduleID) ? '' : $generator->moduleID . '/') . $generator->controllerID?>/view', <?= $urlParams ?>, 'edit' => 't']);
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
                

            ],
            'toolbar' => [
                ['content' =>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role' => "modal-remote", 'title' => Yii::t('app','Create new').Yii::t('app','<?= Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>'), 'class' => "btn btn-default"]).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax' => 1, 'class' => "btn btn-default", 'title' => Yii::t('app','Reset Grid')]).
                    '{toggleData}'.
                    '{export}'
                ],
            ],
            'panel' => [
                'type' => "primary", 
                'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title) . Yii::t('app', 'list') . '</h3>',
                'before' => "<em>* 你可以拖动改变单列的宽度；筛选框输入<code>" . \Yii::t('app', '(not set)'). "</code>会只搜索值为空的数据；筛选框输入<code>" . $searchModel::EMPTY_STRING . "</code>会只搜索值为空字符的数据；筛选框输入<code>" . $searchModel::NO_EMPTY . "</code>会只搜索非空数据。</em>",
                'after' => BulkButtonWidget::widget([
                    'buttons' => Html::a('<i class="glyphicon glyphicon-trash"></i>  ' . Yii::t('app', '删除选择'), ["bulkdelete", 'type' => "soft"], [
                        "class" => "btn btn-danger btn-xs",
                        'role' => "modal-remote-bulk",
                        'data-confirm' => false, 'data-method' => false,// for overide yii data api
                        'data-request-method' => "post",
                        'data-confirm-title'=>Yii::t('app','Are you sure?'),
                        'data-confirm-message'=>Yii::t('app','Are you sure want to delete this item?')
                    ]),
                ]).
                '<div class="clearfix"></div>',
            ]
        ])<?=" ?>\n"?>
    </div>
</div>
            </div>
        </div>
    </div>
</div> 
<?='<?php Modal::begin([
    \'id\' => "ajaxCrudModal",
    \'footer\' => "", // always need it for jquery plugin
]); ?>'."\n"?>
<?='<?php Modal::end(); ?>'?>


<?='<?php JsBlock::begin(); ?>' ?>

<?='<script>' ?>

<?='$(function () {
    baguetteBox.run(".gvRowBaguetteBox", {
        animation: "fadeIn"
    });
})' ?>

<?='</script>' ?>

<?='<?php JsBlock::end(); ?>' ?>

