<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\reg\RegExtensionSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="reg-extension-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'title_initial') ?>

    <?= $form->field($model, 'bootstrap') ?>

    <?php // echo $form->field($model, 'service') ?>

    <?php // echo $form->field($model, 'cover') ?>

    <?php // echo $form->field($model, 'brief_introduction') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'author') ?>

    <?php // echo $form->field($model, 'version') ?>

    <?php // echo $form->field($model, 'is_setting') ?>

    <?php // echo $form->field($model, 'is_rule') ?>

    <?php // echo $form->field($model, 'is_merchant_route_map') ?>

    <?php // echo $form->field($model, 'default_config') ?>

    <?php // echo $form->field($model, 'console') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_id') ?>

    <?php // echo $form->field($model, 'updated_id') ?>

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
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
