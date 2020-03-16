<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;
use backend\widgets\ActiveField;

/* @var $this yii\web\View */
/* @var $model backend\modules\common\models\WomPlan */
/* @var $form yii\widgets\ActiveForm */
?>

<section id="content">
    <div class="panel">
        <div class="panel-body">

    <?php $form = ActiveForm::begin([
    'options' => ['class' => 'layui-form mylayui-from'],
//    'fieldConfig' => [
//             'template' => "{label}\n<div class=\"col-lg-3 layui-input-inline\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
//             'labelOptions' => ['class' => 'col-lg-1 layui-form-label'],
//         ],
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'desc')->widget('moxuandi\kindeditor\KindEditor') ?>

    <?= $form->field($model, 'status')    
        ->dropDownList(\backend\modules\common\models\WomPlanStatus::find()
        ->select(['name','id'])
        ->orderBy('id')
        ->indexBy('id')
        ->column(),
        ['prompt'=>'请选择状态']);?>
    <?= $form->field($model, 'time_status')->dropDownList(\backend\modules\common\models\WomPlantimeStatus::map('id', 'name')) ?>

    <?= $form->field($model, 'admin_id')->dropDownList(\common\models\User::map('user_id', 'real_name')) ?>

    <?= $form->field($model, 'start_at')->textInput()->widget(yii\jui\DatePicker::classname(), ['dateFormat' => 'yyyy-MM-dd', 'options' => ['readonly' => true, 'id' => 'filter-date-to'], 'clientOptions' => [ 'changeMonth' => true, 'changeYear' => true, 'yearRange' => '1980:'.date('Y')]]) ?>
            
    <?= $form->field($model, 'end_at')->textInput()->widget(yii\jui\DatePicker::classname(), ['dateFormat' => 'yyyy-MM-dd', 'options' => ['readonly' => true, 'id' => 'filter-date-end'], 'clientOptions' => [ 'changeMonth' => true, 'changeYear' => true, 'yearRange' => '1980:'.date('Y')]]) ?>


    <div align='right'>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'layui-btn' : 'layui-btn layui-btn-normal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

        </div>
    </div>
</section>
