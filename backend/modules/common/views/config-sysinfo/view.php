<?php

use yii\helpers\Html;
use backend\widgets\DetailView;
use yii\helpers\Url;
use backend\assets\AppAsset;
AppAsset::register($this);
/* @var $this yii\web\View */
/* @var $model common\models\config\ConfigSysinfo */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Config Sysinfos'), 'url' => ['index']];

\yii\web\YiiAsset::register($this);
?>
<div class="layui-card-body">
    <div class="view config-sysinfo-view">
            <div class="layui-fluid layui-card" style="padding: 30px 30px;">
            <div class="layui-row">
                <!--<h3><?= Html::encode($this->title) ?></h3>-->
            <p>
                <?= Html::a(Yii::t('app', 'Rupdate'), ['update', 'id' => $model->siteid], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->siteid], [
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
                    'siteid',
            'name',
            'dirname',
            'domain',
            'serveralias',
            'keywords',
            'description',
            'site_point',
            'smarty_id',
            'smarty_app_id',
            'address:ntext',
            'zipcode',
            'tel',
            'fax',
            'email:email',
            'copyright',
            'logo:ntext',
            'banner:ntext',
            'reg_time',
            'begin_time',
            'end_time',
            'basemail:email',
            'mailpwd',
            'record',
            'created_at',
            'updated_at',
            'default_style',
            'contacts',
            'comp_invoice',
            'comp_bank',
            'bank_numb',
                ],
            ]) ?>
            <p>
                <?= Html::a(Yii::t('app', 'Rupdate'), ['update', 'id' => $model->siteid], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->siteid], [
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
