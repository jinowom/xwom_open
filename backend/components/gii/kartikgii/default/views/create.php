<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

echo "<?php\n";
?>

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var <?= ltrim($generator->modelClass, '\\') ?> $model
 */

$this->title = <?= $generator->generateString('Create ' . Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- 面包屑 -->
<?php  echo "<?";?>
= \Yii::$app->view->renderFile('@app/views/public/breadcrumb.php')<?php echo "?>\n";?>
<!-- 面包屑 -->
<div class="layui-card-body">
    <div class="create <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-create">
            <div class="layui-fluid layui-card" style="padding: 30px 30px;">
            <div class="layui-row">
        <!--<h3><?= "<?= " ?>Html::encode($this->title) ?></h3>-->
    <?= "<?= " ?>$this->render('_form', [
        'model' => $model,
    ]) ?>

            </div>
       </div>
    </div>
</div>

