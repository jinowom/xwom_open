<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\xpaper\models\XpMemberSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="xp-member-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user') ?>

    <?php // echo $form->field($model, 'pwd') ?>

    <?php // echo $form->field($model, 'encrypt') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'realname') ?>

    <?php // echo $form->field($model, 'weixin') ?>

    <?php // echo $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'register_time') ?>

    <?php // echo $form->field($model, 'login_time') ?>

    <?php // echo $form->field($model, 'quit_time') ?>

    <?php // echo $form->field($model, 'register_ip') ?>

    <?php // echo $form->field($model, 'last_ip') ?>

    <?php // echo $form->field($model, 'login_num') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'address2') ?>

    <?php // echo $form->field($model, 'groupid') ?>

    <?php // echo $form->field($model, 'lock') ?>

    <?php // echo $form->field($model, 'have_message') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'sex') ?>

    <?php // echo $form->field($model, 'head_portrait_small') ?>

    <?php // echo $form->field($model, 'head_portrait_big') ?>

    <?php // echo $form->field($model, 'qq_id') ?>

    <?php // echo $form->field($model, 'weibo_id') ?>

    <?php // echo $form->field($model, 'forbid') ?>

    <?php // echo $form->field($model, 'forbid_time') ?>

    <?php // echo $form->field($model, 'forbid_keeptime') ?>

    <?php // echo $form->field($model, 'siteid') ?>

    <?php // echo $form->field($model, 'power') ?>

    <?php // echo $form->field($model, 'costtime') ?>

    <?php // echo $form->field($model, 'costendtime') ?>

    <?php // echo $form->field($model, 'comp') ?>

    <?php // echo $form->field($model, 'invoice') ?>

    <?php // echo $form->field($model, 'dutynumbe') ?>

    <?php // echo $form->field($model, 'creditnumbe') ?>

    <?php // echo $form->field($model, 'remarks') ?>

    <?php // echo $form->field($model, 'unionid') ?>

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
