<?php
namespace moxuandi\kindeditor;

use yii\web\AssetBundle;

/**
 * Asset bundle for the KindEditor
 *
 * @author zhangmoxuan <1104984259@qq.com>
 * @link http://www.zhangmoxuan.com
 * @QQ 1104984259
 * @Date 2019-2-8
 * @see http://kindeditor.net
 */
class KindEditorAsset extends AssetBundle
{
    public $sourcePath = '@vendor/moxuandi/yii2-kindeditor/assets';
    public $css = [
        'dist/themes/default/default.css'
    ];
    public $js = [
        'dist/kindeditor-all-min.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
