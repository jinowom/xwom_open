<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var <?= ltrim($generator->modelClass, '\\') ?> $model
 */

$this->title = Yii::t('app', $model-><?= $generator->getNameAttribute() ?>);
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="layui-card-body">
    <div class="view <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view">
            <div class="layui-fluid layui-card" style="padding: 30px 30px;">
            <div class="layui-row">
            <!--<h3><?= "<?= " ?>Html::encode($this->title) ?></h3>-->
            <p>
                
                <?= "<?= " ?>Html::a(<?= $generator->generateString('Delete') ?>, ['delete', <?= $urlParams ?>], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => <?= $generator->generateString('Are you sure you want to delete this item?') ?>,
                        'method' => 'post',
                    ],
                ]) ?>
            </p>

    <?= "<?= " ?>DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => Yii::$app->request->get('edit') == 't' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'panel' => [
            'heading' => $this->title,
            'type' => DetailView::TYPE_INFO,
        ],
        'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        echo "            '" . $name . "',\n";
    }
} else {
    foreach ($generator->getTableSchema()->columns as $column) {

        $format = $generator->generateColumnFormat($column);

        if ($column->type === 'date') {
            echo
"            [
                'attribute' => '$column->name',
                'format' => [
                    'date', (isset(Yii::\$app->modules['datecontrol']['displaySettings']['date']))
                        ? Yii::\$app->modules['datecontrol']['displaySettings']['date']
                        : 'd-m-Y'
                ],
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => DateControl::classname(),
                    'type' => DateControl::FORMAT_DATE
                ]
            ],\n";

        } elseif ($column->type === 'time') {
            echo
"            [
                'attribute' => '$column->name',
                'format' => [
                    'time', (isset(Yii::\$app->modules['datecontrol']['displaySettings']['time']))
                        ? Yii::\$app->modules['datecontrol']['displaySettings']['time']
                        : 'H:i:s A'
                ],
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => DateControl::classname(),
                    'type' => DateControl::FORMAT_TIME
                ]
            ],\n";
        } elseif ($column->type === 'datetime' || $column->type === 'timestamp') {
            echo
"            [
                'attribute' => '$column->name',
                'format' => [
                    'datetime', (isset(Yii::\$app->modules['datecontrol']['displaySettings']['datetime']))
                        ? Yii::\$app->modules['datecontrol']['displaySettings']['datetime']
                        : 'd-m-Y H:i:s A'
                ],
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => DateControl::classname(),
                    'type' => DateControl::FORMAT_DATETIME
                ]
            ],\n";
        } elseif ($column->type === 'created_at' || $column->type === 'timestamp') {
            echo
"            [
                'attribute' => '$column->name',
                'format' => [
                    'datetime', (isset(Yii::\$app->modules['datecontrol']['displaySettings']['datetime']))
                        ? Yii::\$app->modules['datecontrol']['displaySettings']['datetime']
                        : 'd-m-Y H:i:s A'
                ],
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => DateControl::classname(),
                    'type' => DateControl::FORMAT_DATETIME
                ]
            ],\n";
        } elseif ($column->type === 'updated_at') {
            echo
"            [
                'attribute' => '$column->name',
                'format' => [
                    'time', (isset(Yii::\$app->modules['datecontrol']['displaySettings']['time']))
                        ? Yii::\$app->modules['datecontrol']['displaySettings']['time']
                        : 'H:i:s A'
                ],
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => DateControl::classname(),
                    'type' => DateControl::FORMAT_TIME
                ]
            ],\n";
        } else {
            echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        }
    }
}
?>
        ],
        'deleteOptions' => [
            'url' => ['delete', 'id' => $model-><?=$generator->getTableSchema()->primaryKey[0]?>],
        ],
        'enableEditMode' => true,
    ]) ?>
            <p>
                
                <?= "<?= " ?>Html::a(<?= $generator->generateString('Delete') ?>, ['delete', <?= $urlParams ?>], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => <?= $generator->generateString('Are you sure you want to delete this item?') ?>,
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
            </div>
       </div>
    </div>
</div>
