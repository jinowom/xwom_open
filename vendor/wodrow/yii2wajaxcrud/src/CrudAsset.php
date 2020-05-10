<?php 

namespace wodrow\wajaxcrud;

use yii\web\AssetBundle;

/**
 * @author John Martin <john.itvn@gmail.com>
 * @since 1.0
 */
class CrudAsset extends AssetBundle
{
    public $sourcePath = '@wajaxcrud/assets';

//    public $publishOptions = [
//        'forceCopy' => true,
//    ];

    public $css = [
        'ajaxcrud.css',
        'baguetteBox.min.css',
    ];

    public $js = [
        YII_ENV_DEV?'ModalRemote.min.js':'ModalRemote.js',
        YII_ENV_DEV?'ajaxcrud.min.js':'ajaxcrud.js',
        'baguetteBox.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'kartik\grid\GridViewAsset',
    ];
}
