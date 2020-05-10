<?php
namespace oonne\sortablegrid;

use yii\web\AssetBundle;

class SortableGridAsset extends AssetBundle
{
    public $sourcePath = '@vendor/oonne/yii2-sortable-grid-view/assets';

    public $js = [
        'js/jquery.sortable.gridview.js',
    ];

    public $depends = [
        'yii\jui\JuiAsset',
    ];
}
