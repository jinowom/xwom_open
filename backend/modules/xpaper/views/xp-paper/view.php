<?php

use yii\helpers\Html;
use backend\widgets\DetailView;
use yii\helpers\Url;
use backend\assets\AppAsset;
AppAsset::register($this);
/* @var $this yii\web\View */
/* @var $model backend\modules\xpaper\models\XpPaper */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Xp Papers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="layui-card-body">
    <div class="view xp-paper-view">
            <div class="layui-fluid layui-card" style="padding: 30px 30px;">
            <div class="layui-row">
                <!--<h3><?= Html::encode($this->title) ?></h3>-->
            <?= DetailView::widget([
                'model' => $model,
                        'options' => ['class' => 'layui-table'],
                        'template' => '<tr><th width="100px">{label}</th><td>{value}</td></tr>', 
                'attributes' => [
                    'id',
            'release_id',
            'site_id',
            'name_cn',
            'editionnumber',
            'url:url',
            'pdf',
            'editor_id',
            'editor',
            'created_at',
            'updated_at',
            'filesize',
            'html_url:url',
            'cache',
            'status',
                ],
            ]) ?>

            </div>
       </div>
    </div>
</div>
