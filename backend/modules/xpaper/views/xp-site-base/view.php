<?php

use yii\helpers\Html;
use backend\widgets\DetailView;
use yii\helpers\Url;
use backend\assets\AppAsset;
AppAsset::register($this);
/* @var $this yii\web\View */
/* @var $model backend\modules\xpaper\models\XpSiteBase */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Xp Site Bases'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="layui-card-body">
    <div class="view xp-site-base-view">
            <div class="layui-fluid layui-card" style="padding: 30px 30px;">
            <div class="layui-row">
                <!--<h3><?= Html::encode($this->title) ?></h3>-->
            <?= DetailView::widget([
                'model' => $model,
                        'options' => ['class' => 'layui-table'],
                        'template' => '<tr><th width="100px">{label}</th><td>{value}</td></tr>', 
                'attributes' => [
                    'siteid',
            'appid',
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
            'ftp_folder',
            'auto_folder',
            'begin_time',
            'end_time',
            'client_country',
            'province',
            'city',
            'industry',
            'cache',
            'basemail:email',
            'mailpwd',
            'status',
            'site_open',
            'record',
            'created_at',
            'updated_at',
            'is_show',
            'default_style',
            'islogin',
            'ismempower',
            'contacts',
            'comp_invoice',
            'comp_bank',
            'bank_numb',
            'company_id',
            'paymode',
                ],
            ]) ?>

            </div>
       </div>
    </div>
</div>
