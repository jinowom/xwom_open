<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\xpaper\models\XpNewsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="xp-news-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'order_num') ?>

    <?php // echo $form->field($model, 'special_order') ?>

    <?php // echo $form->field($model, 'special_id') ?>

    <?php // echo $form->field($model, 'emphasis_order') ?>

    <?php // echo $form->field($model, 'emphasis') ?>

    <?php // echo $form->field($model, 'title_eyebrow') ?>

    <?php // echo $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'title_sub') ?>

    <?php // echo $form->field($model, 'author') ?>

    <?php // echo $form->field($model, 'class') ?>

    <?php // echo $form->field($model, 'articleclass') ?>

    <?php // echo $form->field($model, 'imagesnumbers') ?>

    <?php // echo $form->field($model, 'resource') ?>

    <?php // echo $form->field($model, 'foreword') ?>

    <?php // echo $form->field($model, 'keywords') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'paper_id') ?>

    <?php // echo $form->field($model, 'release_id') ?>

    <?php // echo $form->field($model, 'site_id') ?>

    <?php // echo $form->field($model, 'checkserche') ?>

    <?php // echo $form->field($model, 'uploadfile') ?>

    <?php // echo $form->field($model, 'movie_uir') ?>

    <?php // echo $form->field($model, 'paging_type') ?>

    <?php // echo $form->field($model, 'maxcharperpage') ?>

    <?php // echo $form->field($model, 'maxcharimge') ?>

    <?php // echo $form->field($model, 'searchid') ?>

    <?php // echo $form->field($model, 'yes_no_islink') ?>

    <?php // echo $form->field($model, 'islink') ?>

    <?php // echo $form->field($model, 'isdata') ?>

    <?php // echo $form->field($model, 'coordinate') ?>

    <?php // echo $form->field($model, 'canvas_type') ?>

    <?php // echo $form->field($model, 'paper_order') ?>

    <?php // echo $form->field($model, 'animation_takeaway') ?>

    <?php // echo $form->field($model, 'cache') ?>

    <?php // echo $form->field($model, 'click_number') ?>

    <?php // echo $form->field($model, 'like_number') ?>

    <?php // echo $form->field($model, 'status') ?>

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
