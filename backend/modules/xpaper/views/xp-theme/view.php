<?php

use yii\helpers\Html;
use backend\widgets\DetailView;
use yii\helpers\Url;
use backend\assets\AppAsset;
AppAsset::register($this);
/* @var $this yii\web\View */
/* @var $model backend\modules\xpaper\models\XpTheme */

$this->title = $model->theme_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Xp Themes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="layui-card-body">
    <div class="view xp-theme-view">
            <div class="layui-fluid layui-card" style="padding: 30px 30px;">
            <div class="layui-row">
                <!--<h3><?= Html::encode($this->title) ?></h3>-->
            <?= DetailView::widget([
                'model' => $model,
                        'options' => ['class' => 'layui-table'],
                        'template' => '<tr><th width="100px">{label}</th><td>{value}</td></tr>', 
                'attributes' => [
                    'theme_id',
            'theme_name',
            'theme_dir',
            'theme_image',
            'theme_listorder',
            'description',
            'theme_paper_width',
            'siteid',
            'theme_style',
            'theme_html_en',
            'status',
            'public',
                ],
            ]) ?>

            </div>
       </div>
    </div>
</div>
