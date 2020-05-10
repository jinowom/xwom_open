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
$statusField = $generator->statusField;

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>
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

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
    <div id="ajaxCrudDatatable">
        <?="<?= "?>GridView::widget([
            'id' => 'crud-datatable',
            'rowOptions' => [
                'class' => 'gvRowBaguetteBox',
            ],
            'dataProvider' => $dataProvider,
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
                <?php foreach ($generator->getColumnNames() as $name): ?><?php if(in_array($name, $editableFields)): ?>[
                    'class' => EditableColumn::class,
                    'attribute' => "<?=$name ?>",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'readonly' => function ($model, $key, $index, $widget) {
                        return false;
                    },
                    'editableOptions' => function ($model, $key, $index, $widget) {
                        return [
                            'header' => "修改",
                            'size' => "md",
                            'formOptions' => ['action' => ['editable-edit']],
                        ];
                    },
                    'refreshGrid' => true,
                ],
                <?php elseif (in_array($name, $dateRangeFields)): ?>[
                    'class' => DataColumn::class,
                    'attribute' => "<?=$name ?>",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'format' => ['date', 'php:Y-m-d H:i'],
                    'filter' => DateRangePicker::widget([
                        'model' => $searchModel,
                        'attribute' => "<?=$name ?>",
                        'convertFormat' => true,
                        'pluginOptions' => [
                            'opens' => "left",
                            'timePicker' => true,
                            'timePickerIncrement' => 30,
                            'locale' => [
                                'format' => "Y-m-d H:i",
                                'cancelLabel' => "清除",
                            ],
                        ],
//                        'useWithAddon' => true,
//                        'presetDropdown' => true,
                        'pjaxContainerId' => "crud-datatable-pjax",
                    ]),
                ],
                <?php elseif (in_array($name, $thumbImageFields)): ?>[
                    'class' => DataColumn::class,
                    'attribute' => "<?=$name ?>",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'mergeHeader' => true,
                    'enableSorting' => false,
                    'format' => 'raw',
                    'value' => function ($m) {
                        return Html::a(Html::img($m-><?=$name ?>, ['alt' => '缩略图', 'width' => 120]), $m-><?=$name ?>);
                    },
                ],
                <?php elseif ($name == $statusField): ?>[
                    'class' => EnumColumn::class,
                    'attribute' => "<?=$name ?>",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'enum' => Status::getStatus(),
                ],
                <?php else: ?>[
                    'class' => DataColumn::class,
                    'attribute' => "<?=$name ?>",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                ],
                <?php endif; ?><?php endforeach; ?>[
                    'class' => ActionColumn::class,
                    'dropdown' => false,
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'urlCreator' => function($action, $model, $key, $index) {
                        return Url::to([$action,'<?=substr($actionParams,1)?>' => $key, 'type' => "soft"]);
                    },
                    'viewOptions' => ['role' => "modal-remote", 'title' => "View",'data-toggle' => "tooltip"],
                    'updateOptions' => ['role' => 'modal-remote', 'title' => "Update", 'data-toggle' => "tooltip"],
                    'deleteOptions' => [
                        'role' => 'modal-remote',
                        'title' => "Delete",
                        'data-confirm' => false,
                        'data-method' => false, // for overide yii data api
                        'data-request-method' => "post",
                        'data-toggle' => "tooltip",
                        'data-confirm-title' => "删除数据提示!",
                        'data-confirm-message' => "你确认要删除本条数据吗?",
                    ],
                ],
                [
                    'class' => DataColumn::class,
                    'label' => '更多操作',
                    'format' => 'raw',
                    'mergeHeader' => true,
                    'value' => function ($m) {
                        return Html::a('操作名test', ['test', 'id' => $m->id], [
                            'title' => 'title',
                            'role' => 'modal-remote',
                            'data-toggle' => 'tooltip',
                        ]);
                    },
                    'contentOptions' => ['style' => 'vertical-align: middle;'],
                ],
            ],
            'toolbar' => [
                ['content' =>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role' => "modal-remote", 'title' => "Create new <?= Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>", 'class' => "btn btn-default"]).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax' => 1, 'class' => "btn btn-default", 'title' => "Reset Grid"]).
                    '{toggleData}'.
                    '{export}'
                ],
            ],
            'panel' => [
                'type' => "primary", 
                'heading' => "<i class=\"glyphicon glyphicon-list\"></i> <?= Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?> 列表",
                'before' => "<em>* 你可以拖动改变单列的宽度；筛选框输入<code>" . \Yii::t('yii', '(not set)'). "</code>会只搜索值为空的数据；筛选框输入<code>" . $searchModel::EMPTY_STRING . "</code>会只搜索值为空字符的数据；筛选框输入<code>" . $searchModel::NO_EMPTY . "</code>会只搜索非空数据。</em>",
                'after' => BulkButtonWidget::widget([
                    'buttons' => Html::a('<i class="glyphicon glyphicon-trash"></i> 删除选择', ["bulkdelete", 'type' => "soft"], [
                        "class" => "btn btn-danger btn-xs",
                        'role' => "modal-remote-bulk",
                        'data-confirm' => false, 'data-method' => false,// for overide yii data api
                        'data-request-method' => "post",
                        'data-confirm-title' => "删除数据提示!",
                        'data-confirm-message' => "你确认要删除这些数据吗?"
                    ]),
                ]).
                '<div class="clearfix"></div>',
            ]
        ])<?=" ?>\n"?>
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

