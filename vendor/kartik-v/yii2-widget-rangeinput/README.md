<h1 align="center">
    <a href="http://demos.krajee.com" title="Krajee Demos" target="_blank">
        <img src="http://kartik-v.github.io/bootstrap-fileinput-samples/samples/krajee-logo-b.png" alt="Krajee Logo"/>
    </a>
    <br>
    yii2-widget-rangeinput
    <hr>
    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DTP3NZQ6G2AYU"
       title="Donate via Paypal" target="_blank">
        <img src="http://kartik-v.github.io/bootstrap-fileinput-samples/samples/donate.png" alt="Donate"/>
    </a>
</h1>

[![Stable Version](https://poser.pugx.org/kartik-v/yii2-widget-rangeinput/v/stable)](https://packagist.org/packages/kartik-v/yii2-widget-rangeinput)
[![Unstable Version](https://poser.pugx.org/kartik-v/yii2-widget-rangeinput/v/unstable)](https://packagist.org/packages/kartik-v/yii2-widget-rangeinput)
[![License](https://poser.pugx.org/kartik-v/yii2-widget-rangeinput/license)](https://packagist.org/packages/kartik-v/yii2-widget-rangeinput)
[![Total Downloads](https://poser.pugx.org/kartik-v/yii2-widget-rangeinput/downloads)](https://packagist.org/packages/kartik-v/yii2-widget-rangeinput)
[![Monthly Downloads](https://poser.pugx.org/kartik-v/yii2-widget-rangeinput/d/monthly)](https://packagist.org/packages/kartik-v/yii2-widget-rangeinput)
[![Daily Downloads](https://poser.pugx.org/kartik-v/yii2-widget-rangeinput/d/daily)](https://packagist.org/packages/kartik-v/yii2-widget-rangeinput)

The RangeInput widget is a customized range slider control widget based on HTML5 range input. The widget enhances the default HTML range input with various features including the following:

* Specially styled for Bootstrap 3.x and Bootstrap 4.x with customizable caption showing the output of the control.
* Ability to prepend and append addons (very useful to show the min and max ranges, and the slider measurement unit).
* Allow the input to be changed both via the control or the text box.
* Automatically degrade to normal text input for unsupported Internet Explorer versions.

> NOTE: This extension is a sub repo split of [yii2-widgets](https://github.com/kartik-v/yii2-widgets). The split has been done since 08-Nov-2014 to allow developers to install this specific widget in isolation if needed. One can also use the extension the previous way with the whole suite of [yii2-widgets](http://demos.krajee.com/widgets).

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/). Check the [composer.json](https://github.com/kartik-v/yii2-widget-rangeinput/blob/master/composer.json) for this extension's requirements and dependencies. Read this [web tip /wiki](http://webtips.krajee.com/setting-composer-minimum-stability-application/) on setting the `minimum-stability` settings for your application's composer.json.

To install, either run

```
$ php composer.phar require kartik-v/yii2-widget-rangeinput "*"
```

or add

```
"kartik-v/yii2-widget-rangeinput": "*"
```

to the ```require``` section of your `composer.json` file.

## Release Changes

Refer the [CHANGE LOG](https://github.com/kartik-v/yii2-widget-rangeinput/blob/master/CHANGE.md) for details on various releases and changes.

## Demo

You can refer detailed [documentation and demos](http://demos.krajee.com/widget-details/rangeinput) on usage of the extension.

## Usage

```php
use kartik\range\RangeInput;

// Usage with rangeinput and model
echo $form->field($model, 'rating')->widget(RangeInput::classname(), [
    'options' => ['placeholder' => 'Select range ...'],
    'html5Options' => ['min'=>0, 'max'=>1, 'step'=>1],
    'html5Container' => ['style' => 'width:350px'],
    'addon' => ['append'=>['content'=>'star']],
    
]);

// With model & without rangeinput
echo '<label class="control-label">Adjust Contrast</label>';
echo RangeInput::widget([
    'model' => $model,
    'attribute' => 'contrast',
    'html5Container' => ['style' => 'width:350px'],
    'addon' => ['append'=>['content'=>'%']],
]);

// Vertical orientation
echo '<label class="control-label">Adjust Contrast</label>';
echo RangeInput::widget([
    'name' => 'slider',
    'value' => 70,
    'orientation' => 'vertical',
    'html5Container' => ['style' => 'width:350px'],
]);
```

## License

**yii2-widget-rangeinput** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.
