<?php
/**
 * @package   yii2-bootstrap4-dropdown
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015 - 2020
 * @version   1.0.1
 */

namespace kartik\bs4dropdown;

use kartik\base\PluginAssetBundle;

/**
 * Dropdown asset bundle.
 */
class DropdownAsset extends PluginAssetBundle
{
    /**
     * @inheritdoc
     */
    public $bsVersion = '4.x';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/dropdown']);
        $this->setupAssets('css', ['css/dropdown']);
        parent::init();
    }
}
