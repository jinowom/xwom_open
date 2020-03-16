<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\xportal\models\XportalChannelSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="xportal-channel-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'channel_id') ?>

    <?= $form->field($model, 'channel_ch_name') ?>

    <?php // echo $form->field($model, 'channel_en_name') ?>

    <?php // echo $form->field($model, 'channel_alias') ?>

    <?php // echo $form->field($model, 'channel_listorder') ?>

    <?php // echo $form->field($model, 'channel_theme_type') ?>

    <?php // echo $form->field($model, 'channel_type') ?>

    <?php // echo $form->field($model, 'channel_theme_id') ?>

    <?php // echo $form->field($model, 'channel_description') ?>

    <?php // echo $form->field($model, 'bank_url') ?>

    <?php // echo $form->field($model, 'ismenu') ?>

    <?php // echo $form->field($model, 'index_ismenu') ?>

    <?php // echo $form->field($model, 'parameter') ?>

    <?php // echo $form->field($model, 'cache') ?>

    <?php // echo $form->field($model, 'pic') ?>

    <?php // echo $form->field($model, 'app_ismenu') ?>

    <?php // echo $form->field($model, 'default_subscribe_channel') ?>

    <?php // echo $form->field($model, 'app_sort') ?>

    <?php // echo $form->field($model, 'channel_top') ?>

    <?php // echo $form->field($model, 'siteid') ?>

    <?php // echo $form->field($model, 'app_channel_theme') ?>

    <!--开发调试后，请删除如上注释部分-->
    <!--用法示例，开发调试后，请删除如下 start-->
    <?php  //echo (, 'username')->textInput(['maxlength' => 20]) ?>

    <?php  //echo (, 'password')->passwordInput(['maxlength' => 20])  ?>

    <?php  //echo (, 'sex')->radioList(['1'=>'男','0'=>'女'])   ?>

    <?php  //echo (, 'edu')->dropDownList(['1'=>'大学','2'=>'高中','3'=>'初中'],['prompt'=>'请选择','style'=>'width:120px'])  ?>

    <?php  //echo (, 'file')->fileInput()  ?>

    <?php  //echo (, 'hobby')->checkboxList(['0'=>'篮球','1'=>'足球','2'=>'羽毛球','3'=>'乒乓球'])  ?>

    <?php  //echo (, 'info')->textarea(['rows'=>3])  ?>

    <?php  //echo (, 'userid')->hiddenInput(['value'=>3])  ?>

 <!--用法示例，开发调试后，请删除如上，end-->
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
