<?php

use kartik\form\ActiveForm;
use kartik\tree\Module;
use kartik\tree\TreeView;
use kartik\tree\models\Tree;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kiwi\Kiwi;

/**
 * @var View       $this
 * @var Tree       $node
 * @var ActiveForm $form
 * @var string     $keyAttribute
 * @var string     $nameAttribute
 * @var string     $iconAttribute
 * @var string     $iconTypeAttribute
 * @var string     $iconsList
 * @var string     $action
 * @var array      $breadcrumbs
 * @var array      $nodeAddlViews
 * @var mixed      $currUrl
 * @var bool       $showIDAttribute
 * @var bool       $showFormButtons
 */

$js = <<<JS
// Helper function to get parameters from the query string.
function getUrlParam(paramName) {
    var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
    var match = window.location.search.match(reParam) ;

    return (match && match.length > 1) ? match[1] : '' ;
}

$().ready(function() {
    var funcNum = getUrlParam('CKEditorFuncNum');

    var elf = $('#elfinder').elfinder({
        getFileCallback : function(file) {
            window.opener.CKEDITOR.tools.callFunction(funcNum, file.url);
            elf.destroy();
            window.close();
        },
        resizable: false
    }).elfinder('instance');
});
JS;
$this->registerJs($js);
?>

<div class="node-form">
    <?= $form->field($node, 'url_key')->textInput(); ?>
    <?= $node->isRoot() ? $form->field($node, 'sort')->textInput() : '' ?>
    <?= $form->field($node, 'content')->widget(\dosamigos\ckeditor\CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full',
        'clientOptions' => [
            'filebrowserBrowseUrl' => Url::to(['/elfinder-page/manager', 'lang' => 'zh_CN', 'filter' => 'image']),
            'filebrowserUploadUrl' => false, //Url::to(['/elfinder-page/connect', 'lang' => 'zh_CN', 'filter' => 'image']),
            'language' => 'zh-CN',
        ]
    ]) ?>
</div>