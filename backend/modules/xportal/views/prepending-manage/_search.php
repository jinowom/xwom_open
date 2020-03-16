<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\xportal\models\XportalPushDataSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="xportal-push-data-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'push_id') ?>

    <?= $form->field($model, 'push_siteid') ?>

    <?php // echo $form->field($model, 'push_news_id') ?>

    <?php // echo $form->field($model, 'push_year') ?>

    <?php // echo $form->field($model, 'push_month') ?>

    <?php // echo $form->field($model, 'push_papername') ?>

    <?php // echo $form->field($model, 'push_issue') ?>

    <?php // echo $form->field($model, 'push_date') ?>

    <?php // echo $form->field($model, 'push_pagename') ?>

    <?php // echo $form->field($model, 'push_title_eyebrow') ?>

    <?php // echo $form->field($model, 'push_title') ?>

    <?php // echo $form->field($model, 'push_title_sub') ?>

    <?php // echo $form->field($model, 'push_author') ?>

    <?php // echo $form->field($model, 'push_foreword') ?>

    <?php // echo $form->field($model, 'push_keywords') ?>

    <?php // echo $form->field($model, 'push_content') ?>

    <?php // echo $form->field($model, 'push_uploadfile') ?>

    <?php // echo $form->field($model, 'push_resource') ?>

    <?php // echo $form->field($model, 'push_cms_category') ?>

    <?php // echo $form->field($model, 'push_cms_siteid') ?>

    <?php // echo $form->field($model, 'push_islink') ?>

    <?php // echo $form->field($model, 'push_yes_no_islink') ?>

    <?php // echo $form->field($model, 'ifpass') ?>

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
