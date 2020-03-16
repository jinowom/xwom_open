<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot'; //定义了我们资源路径为，@webroot即当前web服务器路径
    public $baseUrl = '@web'; //设定我们的url为当前url
    //全局CSS //添加了basePath/css/site.css文件
    public $css = [
        'css/font.css',
        'css/xadmin.css',
    ];
    //全局JS  js文件为空
    public $js = [
        'js/jquery.min.js',
        'lib/layui/layui.js',
        'js/common.js',
        'js/xadmin.js',
    ];
    public $jsOptions = ['position' => View::POS_HEAD];
    //依赖关系 
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    //定义按需加载JS方法，注意加载顺序在最后
    public static function addScript($view, $jsFile){
        $view->registerJsFile($jsFile,[AppAsset::className(), 'depends' => 'api\assets\AppAsset']);
    }

    //定义按需加载css方法，注意加载顺序在最后
    public static function addCss($view, $cssFile){
        $view->registerJsFile($cssFile,[AppAsset::className(), 'depends' => 'api\assets\AppAsset']);
    }

}
