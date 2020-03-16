<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\xportal\models\XportalCategorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="xportal-category-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'catid') ?>

    <?= $form->field($model, 'catname') ?>

    <?php // echo $form->field($model, 'letter') ?>

    <?php // echo $form->field($model, 'alias') ?>

    <?php // echo $form->field($model, 'module') ?>

    <?php // echo $form->field($model, 'category_theme') ?>

    <?php // echo $form->field($model, 'temparticle') ?>

    <?php // echo $form->field($model, 'listorder') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'category_type') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'bank_url') ?>

    <?php // echo $form->field($model, 'parentdir') ?>

    <?php // echo $form->field($model, 'catdir') ?>

    <?php // echo $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'items') ?>

    <?php // echo $form->field($model, 'hits') ?>

    <?php // echo $form->field($model, 'setting') ?>

    <?php // echo $form->field($model, 'ismenu') ?>

    <?php // echo $form->field($model, 'parameter') ?>

    <?php // echo $form->field($model, 'pic') ?>

    <?php // echo $form->field($model, 'sethtml') ?>

    <?php // echo $form->field($model, 'corank') ?>

    <?php // echo $form->field($model, 'siteid') ?>

    <?php // echo $form->field($model, 'cache') ?>

    <?php // echo $form->field($model, 'app_category_theme') ?>

    <?php // echo $form->field($model, 'app_content_theme') ?>

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
