<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\xportal\models\XportalNewsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="xportal-news-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'catid') ?>

    <?php // echo $form->field($model, 'channelid') ?>

    <?php // echo $form->field($model, 'title_eyebrow') ?>

    <?php // echo $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'title_sub') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'author') ?>

    <?php // echo $form->field($model, 'keywords') ?>

    <?php // echo $form->field($model, 'listorder') ?>

    <?php // echo $form->field($model, 'groupids_view') ?>

    <?php // echo $form->field($model, 'news_checkserche') ?>

    <?php // echo $form->field($model, 'news_uploadfile') ?>

    <?php // echo $form->field($model, 'news_movie_uir') ?>

    <?php // echo $form->field($model, 'movie_blankurl') ?>

    <?php // echo $form->field($model, 'paging_type') ?>

    <?php // echo $form->field($model, 'maxcharperpage') ?>

    <?php // echo $form->field($model, 'maxcharimge') ?>

    <?php // echo $form->field($model, 'relation') ?>

    <?php // echo $form->field($model, 'allow_comment') ?>

    <?php // echo $form->field($model, 'copyfrom') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'islink') ?>

    <?php // echo $form->field($model, 'yes_no_islink') ?>

    <?php // echo $form->field($model, 'username') ?>

    <?php // echo $form->field($model, 'click_number') ?>

    <?php // echo $form->field($model, 'inputime') ?>

    <?php // echo $form->field($model, 'updatetime') ?>

    <?php // echo $form->field($model, 'index_listorder') ?>

    <?php // echo $form->field($model, 'channel_listorder') ?>

    <?php // echo $form->field($model, 'is_image') ?>

    <?php // echo $form->field($model, 'arrparent_catid') ?>

    <?php // echo $form->field($model, 'update_username') ?>

    <?php // echo $form->field($model, 'rejection_reason') ?>

    <?php // echo $form->field($model, 'use_catid') ?>

    <?php // echo $form->field($model, 'cache') ?>

    <?php // echo $form->field($model, 'ranking_position') ?>

    <?php // echo $form->field($model, 'news_author_id') ?>

    <?php // echo $form->field($model, 'shuffling') ?>

    <?php // echo $form->field($model, 'thumbnail') ?>

    <?php // echo $form->field($model, 'shuffling_index') ?>

    <?php // echo $form->field($model, 'shuffling_channel') ?>

    <?php // echo $form->field($model, 'news_uploadfile_describe') ?>

    <?php // echo $form->field($model, 'news_movie_uir_describe') ?>

    <?php // echo $form->field($model, 'news_discuss_num') ?>

    <?php // echo $form->field($model, 'siteid') ?>

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
