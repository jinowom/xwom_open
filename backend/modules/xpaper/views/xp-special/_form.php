<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\xpaper\models\XpSpecial */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="xp-special-form create_box">

    <?php $form = ActiveForm::begin([
    'options' => ['class' => 'layui-form'],
    //'fieldConfig' => [
    //         'template' => "{label}\n<div class=\"col-lg-3 layui-input-inline\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
    //         'labelOptions' => ['class' => 'col-lg-1 layui-form-label'],
    //     ],
    ]); ?>

    <?= $form->field($model, 'siteid')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'thumb')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'banner')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'ishtml')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'ispage')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'filename')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'adminid')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'userid')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'createtime')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'listorder')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'elite')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'status')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'cache')->textInput()->textInput(['class'=>'layui-input']) ?>

    <div align='right'>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'layui-btn' : 'layui-btn layui-btn-normal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
