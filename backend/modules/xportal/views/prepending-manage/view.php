<?php

use yii\helpers\Html;
use backend\widgets\DetailView;
use yii\helpers\Url;
use backend\assets\AppAsset;
AppAsset::register($this);
/* @var $this yii\web\View */
/* @var $model backend\modules\xportal\models\XportalPushData */

$this->title = $model->push_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Xportal Push Datas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="layui-card-body">
    <div class="view xportal-push-data-view">
            <div class="layui-fluid layui-card" style="padding: 30px 30px;">
            <div class="layui-row">
                <!--<h3><?= Html::encode($this->title) ?></h3>-->
            <p>
                <?= Html::a(Yii::t('app', 'Rupdate'), ['update', 'id' => $model->push_id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->push_id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
            <?= DetailView::widget([
                'model' => $model,
                        'options' => ['class' => 'layui-table'],
                        'template' => '<tr><th width="100px">{label}</th><td>{value}</td></tr>', 
                'attributes' => [
                    'push_id',
            'push_siteid',
            'push_news_id',
            'push_year',
            'push_month',
            'push_papername',
            'push_issue',
            'push_date',
            'push_pagename',
            'push_title_eyebrow',
            'push_title',
            'push_title_sub',
            'push_author',
            'push_foreword',
            'push_keywords',
            'push_content:ntext',
            'push_uploadfile',
            'push_resource',
            'push_cms_category',
            'push_cms_siteid',
            'push_islink',
            'push_yes_no_islink',
            'ifpass',
                ],
            ]) ?>
            <p>
                <?= Html::a(Yii::t('app', 'Rupdate'), ['update', 'id' => $model->push_id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->push_id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
            </div>
       </div>
    </div>
</div>
