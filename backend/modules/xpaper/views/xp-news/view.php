<?php

use yii\helpers\Html;
use backend\widgets\DetailView;
use yii\helpers\Url;
use backend\assets\AppAsset;
AppAsset::register($this);
/* @var $this yii\web\View */
/* @var $model backend\modules\xpaper\models\XpNews */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Xp News'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="layui-card-body">
    <div class="view xp-news-view">
            <div class="layui-fluid layui-card" style="padding: 30px 30px;">
            <div class="layui-row">
                <!--<h3><?= Html::encode($this->title) ?></h3>-->
            <?= DetailView::widget([
                'model' => $model,
                        'options' => ['class' => 'layui-table'],
                        'template' => '<tr><th width="100px">{label}</th><td>{value}</td></tr>', 
                'attributes' => [
                    'id',
            'order_num',
            'special_order',
            'special_id',
            'emphasis_order',
            'emphasis',
            'title_eyebrow',
            'title',
            'title_sub',
            'author',
            'class',
            'articleclass',
            'imagesnumbers',
            'resource',
            'foreword',
            'keywords',
            'content:ntext',
            'type',
            'created_at',
            'updated_at',
            'paper_id',
            'release_id',
            'site_id',
            'checkserche',
            'uploadfile',
            'movie_uir',
            'paging_type',
            'maxcharperpage',
            'maxcharimge',
            'searchid',
            'yes_no_islink',
            'islink',
            'isdata',
            'coordinate',
            'canvas_type',
            'paper_order',
            'animation_takeaway',
            'cache',
            'click_number',
            'like_number',
            'status',
                ],
            ]) ?>

            </div>
       </div>
    </div>
</div>
