<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kiwi\Kiwi;

/* @var $this yii\web\View */
/* @var $model yigou\page\models\Page */
/* @var $form yii\widgets\ActiveForm */
$pageCategoryClass = Kiwi::getPageCategoryClass();
?>

<div class="page-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'html')->widget(\dosamigos\ckeditor\CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full',
        'clientOptions' => [
            'filebrowserBrowseUrl' => Url::to(['/elfinder-page/manager', 'lang' => 'zh_CN', 'filter' => 'image']),
            'filebrowserUploadUrl' => false, //Url::to(['/elfinder-page/connect', 'lang' => 'zh_CN', 'filter' => 'image']),
            'language' => 'zh-cn',
        ]
    ]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'url_key')->textInput() ?>

    <?= $form->field($model, 'category_id')->widget('kartik\tree\TreeViewInput', [
        'query' => $pageCategoryClass::find()->addOrderBy('root, lft'),
        'headingOptions' => ['label' => $model->getAttributeLabel('category_id')],
        'rootOptions' => ['label' => '<i class="fa fa-tree text-success"></i>'],
        'fontAwesome' => true,
        'asDropdown' => true,
        'multiple' => false,
        'options' => ['disabled' => false]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('yigou_page', 'Create') : Yii::t('yigou_page', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
