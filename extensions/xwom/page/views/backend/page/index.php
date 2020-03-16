<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('yigou_page', 'Pages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('yigou_page', 'Create Page'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'sort',
            'url_key',
            ['attribute' => 'category_id', 'value' => function($model) {
                /** @var $model \yigou\page\models\Page */
                return $model->pageCategory->name;
            }],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
