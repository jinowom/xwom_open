<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model yigou\page\models\Page */

$this->title = $model->page_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yigou_page', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('yigou_page', 'Update'), ['update', 'id' => $model->page_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('yigou_page', 'Delete'), ['delete', 'id' => $model->page_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yigou_page', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'page_id',
            'title',
            'html:ntext',
            'sort',
            'url_key',
            ['attribute' => 'category_id', 'value' => $model->pageCategory->name],
        ],
    ]) ?>

</div>
