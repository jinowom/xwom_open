<?= $generator->title ?>

<?= str_repeat('=', mb_strlen($generator->title, \Yii::$app->charset)) ?>

<?= $generator->description ?>


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist <?= $generator->vendorName ?>/<?= $generator->packageName ?> "*"
```

or add

```
"<?= $generator->vendorName ?>/<?= $generator->packageName ?>": "*"
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
<?= "<?= \\{$generator->namespace}AutoloadExample::widget(); ?>" ?>
```