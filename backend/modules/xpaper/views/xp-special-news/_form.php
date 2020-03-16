<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\xpaper\models\XpNews */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="xp-news-form create_box">

    <?php $form = ActiveForm::begin([
    'options' => ['class' => 'layui-form'],
    //'fieldConfig' => [
    //         'template' => "{label}\n<div class=\"col-lg-3 layui-input-inline\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
    //         'labelOptions' => ['class' => 'col-lg-1 layui-form-label'],
    //     ],
    ]); ?>

    <?= $form->field($model, 'order_num')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'special_order')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'special_id')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'emphasis_order')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'emphasis')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'title_eyebrow')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'title_sub')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'class')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'articleclass')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'imagesnumbers')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'resource')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'foreword')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'keywords')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'type')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'created_at')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'updated_at')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'paper_id')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'release_id')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'site_id')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'checkserche')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'uploadfile')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'movie_uir')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'paging_type')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'maxcharperpage')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'maxcharimge')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'searchid')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'yes_no_islink')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'islink')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'isdata')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'coordinate')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'canvas_type')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'paper_order')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'animation_takeaway')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'cache')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'click_number')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'like_number')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'status')->textInput()->textInput(['class'=>'layui-input']) ?>

    <div align='right'>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'layui-btn' : 'layui-btn layui-btn-normal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
