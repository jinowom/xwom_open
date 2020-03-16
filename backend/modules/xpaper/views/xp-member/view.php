<?php

use yii\helpers\Html;
use backend\widgets\DetailView;
use yii\helpers\Url;
use backend\assets\AppAsset;
AppAsset::register($this);
/* @var $this yii\web\View */
/* @var $model backend\modules\xpaper\models\XpMember */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Xp Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="layui-card-body">
    <div class="view xp-member-view">
            <div class="layui-fluid layui-card" style="padding: 30px 30px;">
            <div class="layui-row">
                <!--<h3><?= Html::encode($this->title) ?></h3>-->
            <?= DetailView::widget([
                'model' => $model,
                        'options' => ['class' => 'layui-table'],
                        'template' => '<tr><th width="100px">{label}</th><td>{value}</td></tr>', 
                'attributes' => [
                    'id',
            'user',
            'pwd',
            'encrypt',
            'name',
            'realname',
            'weixin',
            'mobile',
            'register_time:datetime',
            'login_time:datetime',
            'quit_time:datetime',
            'register_ip',
            'last_ip',
            'login_num',
            'email:email',
            'address',
            'address2',
            'groupid',
            'lock',
            'have_message',
            'status',
            'sex',
            'head_portrait_small',
            'head_portrait_big',
            'qq_id',
            'weibo_id',
            'forbid',
            'forbid_time',
            'forbid_keeptime',
            'siteid',
            'power',
            'costtime:datetime',
            'costendtime:datetime',
            'comp',
            'invoice',
            'dutynumbe',
            'creditnumbe',
            'remarks',
            'unionid',
                ],
            ]) ?>

            </div>
       </div>
    </div>
</div>
