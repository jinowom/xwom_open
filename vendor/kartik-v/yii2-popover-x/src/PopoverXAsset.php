<?php
/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2020
 * @package yii2-popover-x
 * @version 1.3.5
 */

namespace kartik\popover;
use kartik\base\PluginAssetBundle;

/**
 * Asset bundle for PopoverX widget. Includes assets from
 * bootstrap-popover-x plugin by Krajee.
 *
 * @see http://plugins.krajee.com/popover-x
 * @see http://github.com/kartik-v/bootstrap-popover-x
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class PopoverXAsset extends PluginAssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath('@vendor/kartik-v/bootstrap-popover-x');
        $this->setupAssets('css', ['css/bootstrap-popover-x']);
        $this->setupAssets('js', ['js/bootstrap-popover-x']);
        parent::init();
    }
}
