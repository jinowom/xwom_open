<?php

namespace nickdenry\grid\toggle\assets;

use yii\web\AssetBundle;

/**
 * Round switch asset bundle.
 */
class RoundSwitchAsset extends AssetBundle
{
    public $sourcePath = '@nickdenry/grid/toggle/web';
    public $js = [
        'js/round-switch.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
