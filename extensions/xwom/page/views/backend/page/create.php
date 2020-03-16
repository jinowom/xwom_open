<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model yigou\page\models\Page */

$this->title = Yii::t('yigou_page', 'Create Page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yigou_page', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
