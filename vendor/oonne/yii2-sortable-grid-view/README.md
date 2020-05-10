Sortable GridView Widget for Yii2
========================
Yii2 GridView widget base on jQuery UI sortable widget.

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist "oonne/yii2-sortable-grid-view" "*"
```

or add

```
"oonne/yii2-sortable-grid-view" : "*"
```

to the require section of your `composer.json` file.

Usage
------------
* Add to your database new `unsigned int` attribute, such `sequence`.

* Add new behavior in the active record model, for example:

```php
use oonne\sortablegrid\SortableGridBehavior;

public function behaviors()
{
    return [
        'sort' => [
            'class' => SortableGridBehavior::className(),
            'sortableAttribute' => 'sequence'
        ],
    ];
}
```

* Add action in the controller, for example:

```php
use oonne\sortablegrid\SortableGridAction;

public function actions()
{
    return [
        'sort' => [
            'class' => SortableGridAction::className(),
            'modelName' => Model::className(),
        ],
    ];
}
```

* Add SortableGridView in the view, for example:


```php
use oonne\sortablegrid\SortableGridView;

SortableGridView::widget([
    'dataProvider' => $dataProvider,
    'sortableAction' => ['/bannersuper/sort'],
    'columns' => [
        [
            'class' => 'yii\grid\SerialColumn',
            'contentOptions' => ['class' => 'sortable-handle'],
        ],
        [
            'attribute' => 'sName',
        ],
    ]
])
```
