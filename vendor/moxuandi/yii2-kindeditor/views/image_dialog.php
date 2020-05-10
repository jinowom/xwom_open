<?php
/* @var $this yii\web\View */
/* @var $editorOptions string 配置接口 */
/* @var $buttonOptions array button 按钮的 HTML 属性 */
/* @var $wrapOptions array 最外层 div 的 HTML 属性 */
/* @var $inputId string input 输入域的 ID 属性 */
/* @var $inputValue string input 输入域的 value 属性 */
/* @var $showLocal string 是否显示本地选择框 */
/* @var $showRemote string 是否显示本地选择框 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use moxuandi\kindeditor\KindEditorAsset;

KindEditorAsset::register($this);
$url = Yii::$app->assetManager->getBundle(KindEditorAsset::class)->baseUrl;
$hiddenImg = empty($inputValue);
?>
<?= Html::beginTag('div', $wrapOptions) ?>
    <div class="placeholder <?= $hiddenImg ? '' : 'hidden' ?>">
        <?= Html::button(ArrayHelper::remove($buttonOptions, 'label', '点击选择图片'), $buttonOptions) ?>
    </div>
    <div class="clearfix">
        <?= Html::img($inputValue, ['class' => $hiddenImg ? 'img-show hidden' : 'img-show']) ?>
        <div class="btn-list <?= $hiddenImg ? 'hidden' : '' ?>">
            <?= Html::button(ArrayHelper::remove($buttonOptions, 'resetLabel', '重新选择'), $buttonOptions) ?><br />
            <?= Html::button('删除图片', ['class' => 'btn btn-default btn-delete']) ?>
        </div>
    </div>
<?= Html::endTag('div') ?>
<?php
$css = ".hidden{display:none!important}
.image_dialog{width:100%;display:block;clip:auto;background:#fff}
.image_dialog .placeholder{margin:5px;border:2px dashed #e6e6e6;height:200px;padding-top:100px;text-align:center;background:url({$url}/image.png) center 20px no-repeat;color:#ccc;font-size:18px;position:relative}
.image_dialog .btn-list .btn,.image_dialog .placeholder .btn{font-size:18px;line-height:1.5;padding:6px 20px}
.image_dialog .img-show{float:left;max-width:400px;max-height:200px;margin:5px;padding:5px;border:2px dashed #e6e6e6}
.image_dialog .btn-list{float:left;padding:5px}
.image_dialog .btn-list .btn{float:left;margin-bottom:10px}";
$this->registerCss($css);

$wrapID = $wrapOptions['id'];
$script = <<<JS
KindEditor.ready(function(K){
    var editor = K.editor({$editorOptions});
    K('#{$wrapID} .btn-select').click(function(){
        editor.loadPlugin('image', function(){
            editor.plugin.imageDialog({
                showLocal: $showLocal,
                showRemote: $showRemote,
                imageUrl: K('#{$inputId}').val(),
                clickFn: function(url, title, width, height, border, align){
                    K('#{$inputId}').val(url);
                    $('#{$wrapID} .img-show').attr('src', url).attr('alt', title).removeClass('hidden');
                    $('#{$wrapID} .btn-list').removeClass('hidden');
                    $('#{$wrapID} .placeholder').addClass('hidden');
                    editor.hideDialog();
                }
            })
        });
    });
});
$('#{$wrapID} .btn-delete').on('click', function(){
    $('#{$inputId}').val('');
    $('#{$wrapID} .img-show').attr('src', '').attr('alt', '').addClass('hidden');
    $('#{$wrapID} .btn-list').addClass('hidden');
    $('#{$wrapID} .placeholder').removeClass('hidden');
});
JS;
$this->registerJs($script);
