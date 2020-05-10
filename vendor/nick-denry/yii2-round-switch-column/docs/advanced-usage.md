Advanced usage
---

### 1. Change action for column

You could define your own action for any toggle column like follows:

Setup your toggle attribute at GridView columns section

```php
[
    'class' => RoundSwitchColumn::class,
    'attribute' => 'active',
    'action' => 'toggle-and-send', // by default 'toggle'
    /* other column options, i.e. */
    'headerOptions' => ['width' => 150],
],
```

and your controller

```php
public function actionToggleAndSend()
{
    /* Code */
}
```

or define another toggleAction with custom params, i.e. model custom primary key

```php
public function actions()
{
   return [
        'toggle-extended' => [
            'class' => ToggleAction::class,
            'modelClass' => 'common\models\Model', // Your model class,
            'pkColumn' => 'extended_id', // 'id' by default
        ],
    ];
}
```

### 2. Setup custom column filter

By default `RoundSwitchColumn` provides DropdownList filter with `yes | no` options.
You could change this by set up column `filter` value as usual:

```php
[
    'class' => RoundSwitchColumn::class,
    'attribute' => 'active',
    'action' => 'toggle-and-send', // 'toggle' by default
    /* other column options, i.e. */
    'headerOptions' => ['width' => 150],
    'filter' => [
        'someActive' => 'Active',
        'someInactive' => 'Inactive',
    ];
],
```

default is:

```php
    'filter' => [
        '1' => Yii::t('yii', 'Yes'),
        '0' => Yii::t('yii', 'No'),
    ];
```

### 3. Toggle any values, not boolean only

If you want to toggle any other values except boolean, you could define `switchValues` property in your model

```php
public $switchValues = [
    'is_published' => [ // Attribute name
        'on' => 'yes', // Toggle active value
        'off' => 'no' // Toggle inactive value
    ]
];
```

Values could be any [scalar type](http://php.net/manual/en/language.types.intro.php).

You may also change `switchValues` property name by define it in your app config module section

```php
'roundSwitch' => [
    'class' => 'nickdenry\grid\toggle\Module',
    'switchValues' => 'someAnotherPropertyName',
],
```

