<h1 align="center">
    <a href="http://demos.krajee.com" title="Krajee Demos" target="_blank">
        <img src="http://kartik-v.github.io/bootstrap-fileinput-samples/samples/krajee-logo-b.png" alt="Krajee Logo"/>
    </a>
    <br>
    yii2-popover-x
    <hr>
    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DTP3NZQ6G2AYU"
       title="Donate via Paypal" target="_blank">
        <img src="http://kartik-v.github.io/bootstrap-fileinput-samples/samples/donate.png" alt="Donate"/>
    </a>
</h1>

[![Stable Version](https://poser.pugx.org/kartik-v/yii2-popover-x/v/stable)](https://packagist.org/packages/kartik-v/yii2-popover-x)
[![Untable Version](https://poser.pugx.org/kartik-v/yii2-popover-x/v/unstable)](https://packagist.org/packages/kartik-v/yii2-popover-x)
[![License](https://poser.pugx.org/kartik-v/yii2-popover-x/license)](https://packagist.org/packages/kartik-v/yii2-popover-x)
[![Total Downloads](https://poser.pugx.org/kartik-v/yii2-popover-x/downloads)](https://packagist.org/packages/kartik-v/yii2-popover-x)
[![Monthly Downloads](https://poser.pugx.org/kartik-v/yii2-popover-x/d/monthly)](https://packagist.org/packages/kartik-v/yii2-popover-x)
[![Daily Downloads](https://poser.pugx.org/kartik-v/yii2-popover-x/d/daily)](https://packagist.org/packages/kartik-v/yii2-popover-x)

An extended popover widget for Yii Framework 2 based on the [bootstrap-popover-x jQuery plugin](http://plugins.krajee.com/popover-x) by Krajee. This plugin
is an extended popover JQuery plugin which combines both the popover and bootstrap modal features and includes various new styling enhancements. This widget
can be setup just like the builtin `yii\bootstrap\Modal`, with some additional enhancements.

> NOTE: Refer the [CHANGE LOG](https://github.com/kartik-v/yii2-popover-x/blob/master/CHANGE.md) for details on changes to various releases.

## Features  

The plugin offers these enhanced features:

- The extended popover can be rendered just like a bootstrap modal dialog with the bootstrap popover styling. Since the plugin extends the bootstrap modal,
  all features of the [bootstrap modal](http://getbootstrap.com/javascript/#modals) and its methods are also available.
- Adds a popover footer along with header. Configuration of the HTML content for the popover is much easier, just like a bootstrap modal.
- Specially styles and spaces out bootstrap buttons added in popover footer. 
- Add a close icon/button to a popover window.
- Configure various prebuilt styles/templates. In addition to a default (grey), the bootstrap 3 contextual color styles of `primary`, 
  `info`, `success`, `danger`, and `warning` can be used.
- Control popover placements with respect to the target element. The plugin supports 19 different placement options:
    - auto
    - auto-left
    - auto-right
    - auto-top
    - auto-bottom
    - horizontal
    - vertical
    - right
    - left
    - top
    - bottom
    - top top-left
    - top top-right
    - bottom bottom-left
    - bottom bottom-right
    - left left-top
    - left left-bottom
    - right right-top
    - right right-bottom
- Specially style the popover arrow to be consistent for each contextual color and popover placement.
- Prebuilt CSS styles for controlling appearance and sizes of the popovers.

> NOTE: This extension depends on the [kartik-v/yii2-widgets](https://github.com/kartik-v/yii2-widgets) extension which in turn depends on the 
[yiisoft/yii2-bootstrap](https://github.com/yiisoft/yii2/tree/master/extensions/bootstrap) extension. Check the 
[composer.json](https://github.com/kartik-v/yii2-popover-x/blob/master/composer.json) for this extension's requirements and dependencies. 
Note: Yii 2 framework is still in active development, and until a fully stable Yii2 release, your core yii2-bootstrap packages (and its dependencies) 
may be updated when you install or update this extension. You may need to lock your composer package versions for your specific app, and test 
for extension break if you do not wish to auto update dependencies.

## Demo
You can see detailed [documentation and examples](http://demos.krajee.com/popover-x) on usage of the extension.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

> Note: Check the [composer.json](https://github.com/kartik-v/yii2-popover-x/blob/master/composer.json) for this extension's requirements and dependencies. 
Read this [web tip /wiki](http://webtips.krajee.com/setting-composer-minimum-stability-application/) on setting the `minimum-stability` settings for your application's composer.json.

Either run

```
$ php composer.phar require kartik-v/yii2-popover-x "dev-master"
```

or add

```
"kartik-v/yii2-popover-x": "dev-master"
```

to the ```require``` section of your `composer.json` file.

## Usage

### PopoverX

```php
use kartik\popover\PopoverX;

PopoverX::begin([
    'header' => 'Hello world',
    'footer' => Html::button('View', ['class'=>'btn btn-primary']),
    'toggleButton' => ['class'=>'btn btn-primary'],
]);

echo '<p class="text-justify">' .
    'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.' . 
    '</p>';

PopoverX::end();
```

## License

**yii2-popover-x** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.