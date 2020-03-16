<?php

namespace common\widgets\markdown;

use yii\web\AssetBundle;

/**
 * Class MarkdownAsset
 * @package common\widgets\markdown
 * @author Womtech  email:chareler@163.com
 */
class MarkdownAsset extends AssetBundle
{
    public $language;

    public $css = [
        'css/editormd.min.css',
    ];

    public $js = [
        'editormd.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';
        parent::init();
    }
}