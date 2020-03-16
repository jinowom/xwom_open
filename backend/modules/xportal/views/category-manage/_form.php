<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\xportal\models\XportalCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="xportal-category-form create_box">

    <?php $form = ActiveForm::begin([
    'options' => ['class' => 'layui-form'],
    //'fieldConfig' => [
    //         'template' => "{label}\n<div class=\"col-lg-3 layui-input-inline\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
    //         'labelOptions' => ['class' => 'col-lg-1 layui-form-label'],
    //     ],
    ]); ?>
    <!--ActiveForm 常用表单示例 ：https://www.yii-china.com/post/detail/297.html-->
    
    <?= $form->field($model, 'catname')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'letter')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'module')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'category_theme')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'temparticle')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'listorder')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'type')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'category_type')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'bank_url')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'parentdir')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'catdir')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'items')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'hits')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'setting')->textarea(['rows' => 6])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'ismenu')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'parameter')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'pic')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'sethtml')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'corank')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'siteid')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'cache')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'app_category_theme')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'app_content_theme')->textInput()->textInput(['class'=>'layui-input']) ?>

    <div align='right'>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'layui-btn' : 'layui-btn layui-btn-normal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
