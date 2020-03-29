<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>
use yii\helpers\Html;
use yii\helpers\Url;
use backend\assets\AppAsset;
AppAsset::register($this);
?>
<div class="layui-card-body">
    <div class="update <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-update">
            <div class="layui-fluid layui-card" style="padding: 30px 30px;">
            <div class="layui-row">
                <h3><?= "<?= " ?>Html::encode($this->title) ?></h3>
                <h3><?= "<?= " ?>Html::encode($this->title) ?></h3>
        <?= "<?= " ?>$this->render('_form', [
            'model' => $model,
        ]) ?>

            </div>
       </div>
    </div>  
</div>