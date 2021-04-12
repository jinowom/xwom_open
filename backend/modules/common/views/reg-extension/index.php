<?php
/**
 * This is the view;
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-04-05 18:11
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
/* @var $searchModel common\models\reg\RegExtensionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Reg Extensions');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Xp Specials'), 'url' => ['/xpaper/xp-special/index']];//上级菜单示例
$this->params['breadcrumbs'][] = $this->title;
CrudAsset::register($this);

?>
<!-- 面包屑 -->
<?= \Yii::$app->view->renderFile('@app/views/public/breadcrumb.php')?>
<!-- 面包屑 -->
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
<div class="reg-extension-index">
    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
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
                
                           /*
           [
               'class' => DataColumn::class,
               'attribute' => 'id',
               'hAlign' => GridView::ALIGN_CENTER,
               'vAlign' => GridView::ALIGN_MIDDLE,
           ],
           */
           [
               'class' => EditableColumn::class,
               'attribute' => 'title',
               'hAlign' => GridView::ALIGN_CENTER,
               'vAlign' => GridView::ALIGN_MIDDLE,
               'readonly' => function ($model,$key,$index,$widget)  {
               return false;
               },
               'editableOptions' => function ($model,$key,$index,$widget)   {
               return [
               'header' => '修改',
               'size' => 'md',
               'formOptions' => ['action' => ['editable-edit']],
               ];
               },
               'refreshGrid' => true,
           ],
           [
               'class' => DataColumn::class,
               'attribute' => 'name',
               'hAlign' => GridView::ALIGN_CENTER,
               'vAlign' => GridView::ALIGN_MIDDLE,
           ],
           [
               'class' => DataColumn::class,
               'attribute' => 'title_initial',
               'hAlign' => GridView::ALIGN_CENTER,
               'vAlign' => GridView::ALIGN_MIDDLE,
           ],
           [
               'class' => DataColumn::class,
               'attribute' => 'bootstrap',
               'hAlign' => GridView::ALIGN_CENTER,
               'vAlign' => GridView::ALIGN_MIDDLE,
           ],
           [
               'class' => DataColumn::class,
               'attribute' => 'service',
               'hAlign' => GridView::ALIGN_CENTER,
               'vAlign' => GridView::ALIGN_MIDDLE,
           ],
           [
               'class' => DataColumn::class,
               'attribute' => 'cover',
               'hAlign' => GridView::ALIGN_CENTER,
               'vAlign' => GridView::ALIGN_MIDDLE,
               'mergeHeader' => true,
               'enableSorting' => false,
               'format' => 'raw',
               'value' => function ($m) {
                      return Html::a(Html::img($m->cover, ['alt' => '缩略图', 'width' => 120]), $m->cover);
               },
           ],
           [
               'class' => EditableColumn::class,
               'attribute' => 'brief_introduction',
               'hAlign' => GridView::ALIGN_CENTER,
               'vAlign' => GridView::ALIGN_MIDDLE,
               'readonly' => function ($model,$key,$index,$widget)  {
               return false;
               },
               'editableOptions' => function ($model,$key,$index,$widget)   {
               return [
               'header' => '修改',
               'size' => 'md',
               'formOptions' => ['action' => ['editable-edit']],
               ];
               },
               'refreshGrid' => true,
           ],
           [
               'class' => EditableColumn::class,
               'attribute' => 'description',
               'hAlign' => GridView::ALIGN_CENTER,
               'vAlign' => GridView::ALIGN_MIDDLE,
               'readonly' => function ($model,$key,$index,$widget)  {
               return false;
               },
               'editableOptions' => function ($model,$key,$index,$widget)   {
               return [
               'header' => '修改',
               'size' => 'md',
               'formOptions' => ['action' => ['editable-edit']],
               ];
               },
               'refreshGrid' => true,
           ],
           [
               'class' => EditableColumn::class,
               'attribute' => 'author',
               'hAlign' => GridView::ALIGN_CENTER,
               'vAlign' => GridView::ALIGN_MIDDLE,
               'readonly' => function ($model,$key,$index,$widget)  {
               return false;
               },
               'editableOptions' => function ($model,$key,$index,$widget)   {
               return [
               'header' => '修改',
               'size' => 'md',
               'formOptions' => ['action' => ['editable-edit']],
               ];
               },
               'refreshGrid' => true,
           ],
           // [
               // 'class' => DataColumn::class,
               // 'attribute' => 'version',
               // 'hAlign' => GridView::ALIGN_CENTER,
               // 'vAlign' => GridView::ALIGN_MIDDLE,
           // ],
           // [
               // 'class' => DataColumn::class,
               // 'attribute' => 'is_setting',
               // 'hAlign' => GridView::ALIGN_CENTER,
               // 'vAlign' => GridView::ALIGN_MIDDLE,
           // ],
           // [
               // 'class' => DataColumn::class,
               // 'attribute' => 'is_rule',
               // 'hAlign' => GridView::ALIGN_CENTER,
               // 'vAlign' => GridView::ALIGN_MIDDLE,
           // ],
           // [
               // 'class' => DataColumn::class,
               // 'attribute' => 'is_merchant_route_map',
               // 'hAlign' => GridView::ALIGN_CENTER,
               // 'vAlign' => GridView::ALIGN_MIDDLE,
           // ],
           // [
               // 'class' => DataColumn::class,
               // 'attribute' => 'default_config',
               // 'hAlign' => GridView::ALIGN_CENTER,
               // 'vAlign' => GridView::ALIGN_MIDDLE,
           // ],
           // [
               // 'class' => DataColumn::class,
               // 'attribute' => 'console',
               // 'hAlign' => GridView::ALIGN_CENTER,
               // 'vAlign' => GridView::ALIGN_MIDDLE,
           // ],
           [
               'class' => RoundSwitchColumn::class,
               'attribute' => 'status',
               /* other column options, i.e. */
               'headerOptions' => ['width' => 150],
               //'hAlign' => GridView::ALIGN_CENTER,
               //'vAlign' => GridView::ALIGN_MIDDLE,
           ],
             //  [
              //  'class' => DataColumn::class,
             //   'attribute' => 'created_at',
              //  'hAlign' => GridView::ALIGN_CENTER,
             //   'vAlign' => GridView::ALIGN_MIDDLE,
             //   'format' => ['date', 'php:Y-m-d H:i'],
              //  'filter' => DateRangePicker::widget([
                  //  'model' => $searchModel,
                  //  'attribute' => 'created_at',
                  //  'convertFormat' => true,
                   // 'pluginOptions' => [
                  //  'opens' => 'left',
                   // 'timePicker' => true,
                  //  'timePickerIncrement' => 30,
                   // 'locale' => [
                      // 'format' => 'Y-m-d H:i',
                      // 'cancelLabel' => '清除',
                   // ],
                // ],
                  // //'useWithAddon' => true,
                 //  //'presetDropdown' => true,
                  // 'pjaxContainerId' => 'crud-datatable-pjax',
               // ]),
           // ],
             //  [
              //  'class' => DataColumn::class,
             //   'attribute' => 'updated_at',
              //  'hAlign' => GridView::ALIGN_CENTER,
             //   'vAlign' => GridView::ALIGN_MIDDLE,
             //   'format' => ['date', 'php:Y-m-d H:i'],
              //  'filter' => DateRangePicker::widget([
                  //  'model' => $searchModel,
                  //  'attribute' => 'updated_at',
                  //  'convertFormat' => true,
                   // 'pluginOptions' => [
                  //  'opens' => 'left',
                   // 'timePicker' => true,
                  //  'timePickerIncrement' => 30,
                   // 'locale' => [
                      // 'format' => 'Y-m-d H:i',
                      // 'cancelLabel' => '清除',
                   // ],
                // ],
                  // //'useWithAddon' => true,
                 //  //'presetDropdown' => true,
                  // 'pjaxContainerId' => 'crud-datatable-pjax',
               // ]),
           // ],
           // [
               // 'class' => DataColumn::class,
               // 'attribute' => 'created_id',
               // 'hAlign' => GridView::ALIGN_CENTER,
               // 'vAlign' => GridView::ALIGN_MIDDLE,
           // ],
           // [
               // 'class' => DataColumn::class,
               // 'attribute' => 'updated_id',
               // 'hAlign' => GridView::ALIGN_CENTER,
               // 'vAlign' => GridView::ALIGN_MIDDLE,
           // ],

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
                                                //$url = Yii::$app->urlManager->createUrl(['reg-extension/view', 'id' => $model->id, 'edit' => 't']);
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
                    ['role' => "modal-remote", 'title' => Yii::t('app','Create new').Yii::t('app','Reg Extensions'), 'class' => "btn btn-default"]).
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
        ]) ?>
    </div>
</div>
            </div>
        </div>
    </div>
</div> 
<?php Modal::begin([
    'id' => "ajaxCrudModal",
    'footer' => "", // always need it for jquery plugin
]); ?>
<?php Modal::end(); ?>

<?php JsBlock::begin(); ?>
<script>
$(function () {
    baguetteBox.run(".gvRowBaguetteBox", {
        animation: "fadeIn"
    });
})
</script>
<?php JsBlock::end(); ?>
