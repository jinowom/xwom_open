<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model yigou\page\models\Page */

$this->title = Yii::t('yigou_page', 'Update {modelClass}: ', [
    'modelClass' => 'Page',
]) . ' ' . $model->page_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yigou_page', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->page_id, 'url' => ['view', 'id' => $model->page_id]];
$this->params['breadcrumbs'][] = Yii::t('yigou_page', 'Update');
?>
<div class="page-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
