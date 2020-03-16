<?php

use yii\helpers\Html;
use backend\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\xpaper\models\XpThemeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="xp-theme-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'theme_id') ?>

    <?= $form->field($model, 'theme_name') ?>

    <?php // echo $form->field($model, 'theme_dir') ?>

    <?php // echo $form->field($model, 'theme_image') ?>

    <?php // echo $form->field($model, 'theme_listorder') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'theme_paper_width') ?>

    <?php // echo $form->field($model, 'siteid') ?>

    <?php // echo $form->field($model, 'theme_style') ?>

    <?php // echo $form->field($model, 'theme_html_en') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'public') ?>

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
