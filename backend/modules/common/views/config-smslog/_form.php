<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\log\ConfigSmslog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="config-smslog-form create_box">

    <?php $form = ActiveForm::begin([
    'options' => ['class' => 'layui-form'],
    //'fieldConfig' => [
    //         'template' => "{label}\n<div class=\"col-lg-3 layui-input-inline\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
    //         'labelOptions' => ['class' => 'col-lg-1 layui-form-label'],
    //     ],
    ]); ?>
    <!--ActiveForm 常用表单示例 ：https://www.yii-china.com/post/detail/297.html-->
    
    <?= $form->field($model, 'merchant_id')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'member_id')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'content')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'error_code')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'error_msg')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'error_data')->textarea(['rows' => 6])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'usage')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'used')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'use_time')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'status')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'created_at')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'updated_at')->textInput()->textInput(['class'=>'layui-input']) ?>

    <div align='right'>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'layui-btn' : 'layui-btn layui-btn-normal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
