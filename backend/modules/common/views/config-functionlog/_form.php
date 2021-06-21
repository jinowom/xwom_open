<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\log\ConfigFunctionlog */
/* @var $form \kartik\form\ActiveForm */
?>

<div class="config-functionlog-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'app_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'merchant_id')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'method')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'module')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'controller')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'action')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'get_data')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'post_data')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'header_data')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'error_code')->textInput() ?>

    <?= $form->field($model, 'error_msg')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'error_data')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'req_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_agent')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'device')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'device_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'device_version')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'device_app_version')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
