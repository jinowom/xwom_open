<?php

use yii\helpers\Html;
use backend\widgets\DetailView;
use yii\helpers\Url;
use backend\assets\AppAsset;
AppAsset::register($this);
/* @var $this yii\web\View */
/* @var $model backend\modules\xportal\models\XportalNews */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Xportal News'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="layui-card-body">
    <div class="view xportal-news-view">
            <div class="layui-fluid layui-card" style="padding: 30px 30px;">
            <div class="layui-row">
                <!--<h3><?= Html::encode($this->title) ?></h3>-->
            <p>
                <?= Html::a(Yii::t('app', 'Rupdate'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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
                    'id',
            'catid',
            'channelid',
            'title_eyebrow',
            'title',
            'title_sub',
            'content:ntext',
            'author',
            'keywords',
            'listorder',
            'groupids_view',
            'news_checkserche',
            'news_uploadfile',
            'news_movie_uir',
            'movie_blankurl',
            'paging_type',
            'maxcharperpage',
            'maxcharimge',
            'relation',
            'allow_comment',
            'copyfrom',
            'status',
            'islink',
            'yes_no_islink',
            'username',
            'click_number',
            'inputime:datetime',
            'updatetime:datetime',
            'index_listorder',
            'channel_listorder',
            'is_image',
            'arrparent_catid',
            'update_username',
            'rejection_reason',
            'use_catid',
            'cache',
            'ranking_position',
            'news_author_id',
            'shuffling',
            'thumbnail',
            'shuffling_index',
            'shuffling_channel',
            'news_uploadfile_describe',
            'news_movie_uir_describe',
            'news_discuss_num',
            'siteid',
                ],
            ]) ?>
            <p>
                <?= Html::a(Yii::t('app', 'Rupdate'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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
