<?php
/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2020
 * @package yii2-popover-x
 * @version 1.3.5
 */

namespace kartik\popover;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\base\Widget;
use yii\helpers\Inflector;
use yii\helpers\Json;

/**
 * An extended popover widget for Yii Framework 2 based on the bootstrap-popover-x plugin by Krajee. This widget
 * combines both the popover and bootstrap modal features and includes various new styling enhancements.
 *
 * The following example will show the content enclosed between the [[begin()]] and [[end()]] calls within the popover
 * dialog:
 *
 * ~~~php
 * PopoverX::begin([
 *     'header' => 'Hello world',
 *     'footer' => Html::button('View', ['class'=>'btn btn-primary']),
 *     'toggleButton' => ['label' => 'Open Popover'],
 * ]);
 *
 * echo 'Say hello...';
 *
 * PopoverX::end();
 * ~~~
 *
 * @see http://plugins.krajee.com/popover-x
 * @see http://github.com/kartik-v/bootstrap-popover-x
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class PopoverX extends Widget
{
    /**
     * @var string the **default** bootstrap contextual color type
     */
    const TYPE_DEFAULT = 'default';

    /**
     * @var string the **primary** bootstrap contextual color type
     */
    const TYPE_PRIMARY = 'primary';

    /**
     * @var string the **information** bootstrap contextual color type
     */
    const TYPE_INFO = 'info';

    /**
     * @var string the **danger** bootstrap contextual color type
     */
    const TYPE_DANGER = 'danger';

    /**
     * @var string the **warning** bootstrap contextual color type
     */
    const TYPE_WARNING = 'warning';

    /**
     * @var string the **success** bootstrap contextual color type
     */
    const TYPE_SUCCESS = 'success';

    /**
     * @var string **auto** align popover container and its pointing arrow
     */
    const ALIGN_AUTO = 'auto';

    /**
     * @var string **auto top** align popover container and its pointing arrow
     */
    const ALIGN_AUTO_TOP = 'auto-top';

    /**
     * @var string **auto right** align popover container and its pointing arrow
     */
    const ALIGN_AUTO_RIGHT = 'auto-right';

    /**
     * @var string **auto bottom** align popover container and its pointing arrow
     */
    const ALIGN_AUTO_BOTTOM = 'auto-bottom';

    /**
     * @var string **auto left** align popover container and its pointing arrow
     */
    const ALIGN_AUTO_LEFT = 'auto-left';

    /**
     * @var string **horizontal** align popover container and its pointing arrow
     */
    const ALIGN_AUTO_HORIZONTAL = 'horizontal';

    /**
     * @var string **auto vertical** align popover container and its pointing arrow
     */
    const ALIGN_AUTO_VERTICAL = 'vertical';

    /**
     * @var string **right** align popover container and its pointing arrow
     */
    const ALIGN_RIGHT = 'right';

    /**
     * @var string **left** align popover container and its pointing arrow
     */
    const ALIGN_LEFT = 'left';

    /**
     * @var string **top** align popover container and its pointing arrow
     */
    const ALIGN_TOP = 'top';

    /**
     * @var string **bottom** align popover container and its pointing arrow
     */
    const ALIGN_BOTTOM = 'bottom';

    /**
     * @var string **top** and **top left** align popover container and its pointing arrow
     */
    const ALIGN_TOP_LEFT = 'top top-left';

    /**
     * @var string **bottom** and **bottom left** align popover container and its pointing arrow
     */
    const ALIGN_BOTTOM_LEFT = 'bottom bottom-left';

    /**
     * @var string **top** and **top right** align popover container and its pointing arrow
     */
    const ALIGN_TOP_RIGHT = 'top top-right';

    /**
     * @var string **bottom** and **bottom right** align popover container and its pointing arrow
     */
    const ALIGN_BOTTOM_RIGHT = 'bottom bottom-right';

    /**
     * @var string **left** and **left top** align popover container and its pointing arrow
     */
    const ALIGN_LEFT_TOP = 'left left-top';

    /**
     * @var string **right** and **right top** align popover container and its pointing arrow
     */
    const ALIGN_RIGHT_TOP = 'right right-top';

    /**
     * @var string **left** and **left bottom** align popover container and its pointing arrow
     */
    const ALIGN_LEFT_BOTTOM = 'left left-bottom';

    /**
     * @var string **right** and **right bottom** align popover container and its pointing arrow
     */
    const ALIGN_RIGHT_BOTTOM = 'right right-bottom';

    /**
     * @var string **large** size
     */
    const SIZE_LARGE = 'lg';

    /**
     * @var string **medium** size
     */
    const SIZE_MEDIUM = 'md';

    /**
     * @inheritdoc
     */
    public $pluginName = 'popoverX';

    /**
     * @var string the popover contextual type. Must be one of the [[TYPE]] constants Defaults to
     * `PopoverX::TYPE_DEFAULT` or `default`.
     */
    public $type = self::TYPE_DEFAULT;

    /**
     * @var string the popover placement. Must be one of the [[ALIGN]] constants Defaults to `PopoverX::ALIGN_RIGHT` or
     *     `right`.
     */
    public $placement = self::ALIGN_RIGHT;

    /**
     * @var string the size of the popover dialog. Must be [[PopoverX::SIZE_LARGE]] or [[PopoverX::SIZE_MEDIUM]]
     */
    public $size;

    /**
     * @var string the header content in the popover dialog.
     */
    public $header;

    /**
     * @var array the HTML attributes for the header. The following special options are supported:
     *
     * - tag: string, the HTML tag for rendering the header. Defaults to 'div'.
     *
     */
    public $headerOptions = [];

    /**
     * @var string the body content
     */
    public $content = '';

    /**
     * @var array the HTML attributes for the popover indicator arrow.
     */
    public $arrowOptions = [];

    /**
     * @var string the footer content in the popover dialog.
     */
    public $footer;

    /**
     * @var array the HTML attributes for the footer. The following special
     * options are supported:
     *
     * - tag: string, the HTML tag for rendering the footer. Defaults to 'div'.
     *
     */
    public $footerOptions = [];

    /**
     * @var array the options for rendering the close button tag. The close button is displayed in the header of the
     *     popover dialog. Clicking on the button will hide the popover dialog. If this is null, no close button will
     *     be rendered. The following special options are supported:
     *
     * - tag: string, the HTML tag for rendering the button. Defaults to 'button'.
     * - label: string, the label of the button. Defaults to '&times;'.
     *
     * The rest of the options will be rendered as the HTML attributes of the button tag. Please refer to the [PopoverX
     *     plugin documentation](http://plugins.krajee.com/popover-x) and the [Modal plugin
     *     help](http://getbootstrap.com/javascript/#modals) for the supported HTML attributes.
     */
    public $closeButton = [];

    /**
     * @var array the options for rendering the toggle button tag. The toggle button is used to toggle the visibility
     *     of the popover dialog. If this property is null, no toggle button will be rendered. The following special
     *     options are supported:
     *
     * - tag: string, the tag name of the button. Defaults to 'button'.
     * - label: string, the label of the button. Defaults to 'Show'.
     *
     * The rest of the options will be rendered as the HTML attributes of the button tag. Please refer to the [PopoverX
     *     plugin documentation](http://plugins.krajee.com/popover-x) and the [Modal plugin
     *     help](http://getbootstrap.com/javascript/#modals) for the supported HTML attributes.
     *
     */
    public $toggleButton;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initWidget();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->runWidget();
    }

    /**
     * Initializes the widget
     */
    public function initWidget()
    {
        $this->initBsVersion();
        $this->initOptions();
        echo $this->renderToggleButton() . "\n";
        echo Html::beginTag('div', $this->options) . "\n";
        echo Html::tag('div', '', $this->arrowOptions);
        echo $this->renderHeader() . "\n";
        echo $this->renderBodyBegin() . "\n";
    }

    /**
     * Runs the widget
     */
    public function runWidget()
    {
        echo "\n" . $this->renderBodyEnd();
        echo "\n" . $this->renderFooter();
        echo "\n" . Html::endTag('div');
        $this->registerAssets();
    }

    /**
     * Renders the opening tag of the popover dialog body.
     *
     * @return string the rendering result
     */
    protected function renderBodyBegin()
    {
        return Html::beginTag('div', ['class' => 'popover-body popover-content']);
    }

    /**
     * Renders the closing tag of the popover dialog body.
     *
     * @return string the rendering result
     */
    protected function renderBodyEnd()
    {
        return $this->content . "\n" . Html::endTag('div');
    }

    /**
     * Renders the header HTML markup of the popover dialog.
     *
     * @return string the rendering result
     */
    protected function renderHeader()
    {
        $button = $this->renderCloseButton();
        if ($button !== '') {
            $this->header = $button . "\n" . (empty($this->header) ? '' : $this->header);
        }
        if (empty($this->header)) {
            return '';
        }
        $tag = ArrayHelper::remove($this->headerOptions, 'tag', 'div');
        Html::addCssClass($this->headerOptions, ['popover-header', 'popover-title']);
        return Html::tag($tag, "\n" . $this->header . "\n", $this->headerOptions);
    }

    /**
     * Renders the HTML markup for the footer of the popover dialog.
     *
     * @return string the rendering result
     */
    protected function renderFooter()
    {
        if (empty($this->footer)) {
            return '';
        }
        $tag = ArrayHelper::remove($this->footerOptions, 'tag', 'div');
        Html::addCssClass($this->footerOptions, 'popover-footer');
        return Html::tag($tag, "\n" . $this->footer . "\n", $this->footerOptions);
    }

    /**
     * Renders the toggle button.
     *
     * @return string the rendering result
     */
    protected function renderToggleButton()
    {
        if (!is_array($this->toggleButton) || empty($this->toggleButton)) {
            return '';
        }
        $tag = ArrayHelper::remove($this->toggleButton, 'tag', 'button');
        $label = ArrayHelper::remove($this->toggleButton, 'label', 'Show');
        if ($tag === 'button' && !isset($this->toggleButton['type'])) {
            $this->toggleButton['type'] = 'button';
        }
        return Html::tag($tag, $label, $this->toggleButton);
    }

    /**
     * Renders the close button.
     *
     * @return string the rendering result
     */
    protected function renderCloseButton()
    {
        if (!is_array($this->toggleButton) || empty($this->closeButton)) {
            return '';
        }
        $tag = ArrayHelper::remove($this->closeButton, 'tag', 'button');
        $label = ArrayHelper::remove($this->closeButton, 'label', '&times;');
        if ($tag === 'button' && !isset($this->closeButton['type'])) {
            $this->closeButton['type'] = 'button';
        }
        return Html::tag($tag, $label, $this->closeButton);
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        if (!isset($this->options['role'])) {
            $this->options['role'] = 'dialog';
        }
        $css = ['popover', 'popover-x', "popover-{$this->type}"];
        if ($this->isBs4()) {
            $css[] = 'is-bs4';
        }
        if (isset($this->size)) {
            $css[] = "popover-{$this->size}";
        }
        Html::addCssClass($this->options, $css);
        Html::addCssClass($this->arrowOptions, 'arrow');
        if ($this->pluginOptions !== false) {
            $this->pluginOptions = ArrayHelper::merge($this->pluginOptions, ['show' => false]);
        }
        if ($this->closeButton !== null && is_array($this->closeButton)) {
            $this->closeButton = ArrayHelper::merge($this->closeButton, [
                'data-dismiss' => 'popover-x',
                'aria-hidden' => 'true',
                'class' => 'close',
            ]);
        }
        if ($this->toggleButton !== null && is_array($this->toggleButton)) {
            $opts = [
                'data-toggle' => 'popover-x',
                'data-placement' => $this->placement,
            ];
            if (!empty($this->pluginOptions)) {
                foreach ($this->pluginOptions as $key => $value) {
                    $k = 'data-' . Inflector::camel2id($key);
                    $opts[$k] = is_array($value) ? Json::encode($value) : $value;
                }
            }
            $this->toggleButton = ArrayHelper::merge($this->toggleButton, $opts);
            if (!isset($this->toggleButton['data-target']) && !isset($this->toggleButton['href'])) {
                $this->toggleButton['data-target'] = '#' . $this->options['id'];
            }
        }
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        PopoverXAsset::register($view);
        if ($this->toggleButton === null) {
            $this->registerPlugin($this->pluginName);
        }
    }
}
