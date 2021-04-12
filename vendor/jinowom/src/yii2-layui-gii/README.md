jinowom-layui
=============
yii2-layui-gii

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist jinowom/yii2-layui-gii "*"
```

or add

```
"jinowom/yii2-layui-gii": "*"
```

to the require section of your `composer.json` file.

## Configuration

```php

$config = [
    'components' => [
       // 'workflowSource' => [
       //     'class' => 'jinostart\workflow\manager\components\WorkflowDbSource',
       // ],
    ],
    'modules' => [
        //'workflow' => [
        //    'class' => 'jinostart\workflow\manager\Module',
        //],
    ],
];


```


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \jinowom\layui\crud\AutoloadExample::widget(); ?>```