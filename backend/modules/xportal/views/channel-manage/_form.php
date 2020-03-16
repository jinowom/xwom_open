<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\xportal\models\XportalChannel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="xportal-channel-form create_box">

    <?php $form = ActiveForm::begin([
    'options' => ['class' => 'layui-form'],
    //'fieldConfig' => [
    //         'template' => "{label}\n<div class=\"col-lg-3 layui-input-inline\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
    //         'labelOptions' => ['class' => 'col-lg-1 layui-form-label'],
    //     ],
    ]); ?>
    <!--ActiveForm 常用表单示例 ：https://www.yii-china.com/post/detail/297.html-->
    
    <?= $form->field($model, 'channel_ch_name')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'channel_en_name')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'channel_alias')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'channel_listorder')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'channel_theme_type')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'channel_type')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'channel_theme_id')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'channel_description')->textarea(['rows' => 6])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'bank_url')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'ismenu')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'index_ismenu')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'parameter')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'cache')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'pic')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'app_ismenu')->dropDownList([ 'y' => 'Y', 'n' => 'N', ], ['prompt' => ''])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'default_subscribe_channel')->dropDownList([ 'y' => 'Y', 'n' => 'N', ], ['prompt' => ''])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'app_sort')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'channel_top')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'siteid')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'app_channel_theme')->textInput()->textInput(['class'=>'layui-input']) ?>

    <div align='right'>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'layui-btn' : 'layui-btn layui-btn-normal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
