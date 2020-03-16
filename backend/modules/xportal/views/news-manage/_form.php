<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\xportal\models\XportalNews */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="xportal-news-form create_box">

    <?php $form = ActiveForm::begin([
    'options' => ['class' => 'layui-form'],
    //'fieldConfig' => [
    //         'template' => "{label}\n<div class=\"col-lg-3 layui-input-inline\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
    //         'labelOptions' => ['class' => 'col-lg-1 layui-form-label'],
    //     ],
    ]); ?>
    <!--ActiveForm 常用表单示例 ：https://www.yii-china.com/post/detail/297.html-->
    
    <?= $form->field($model, 'catid')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'channelid')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'title_eyebrow')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'title_sub')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'keywords')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'listorder')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'groupids_view')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'news_checkserche')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'news_uploadfile')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'news_movie_uir')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'movie_blankurl')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'paging_type')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'maxcharperpage')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'maxcharimge')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'relation')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'allow_comment')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'copyfrom')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'status')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'islink')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'yes_no_islink')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'click_number')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'inputime')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'updatetime')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'index_listorder')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'channel_listorder')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'is_image')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'arrparent_catid')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'update_username')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'rejection_reason')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'use_catid')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'cache')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'ranking_position')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'news_author_id')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'shuffling')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'thumbnail')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'shuffling_index')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'shuffling_channel')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'news_uploadfile_describe')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'news_movie_uir_describe')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'news_discuss_num')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'siteid')->textInput()->textInput(['class'=>'layui-input']) ?>

    <div align='right'>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'layui-btn' : 'layui-btn layui-btn-normal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
