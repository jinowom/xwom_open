Yii2文件管理器
================
该模块提供了一个接口，用于在一个地方收集和访问所有媒体文件。

特点
------------
* 与TinyMCE编辑器集成。
* 自动为上传的文件（例如“ 2014/12”）实际创建目录。
* 自动为上传的图片创建缩略图
* 无限数量的缩影
* 所有媒体文件都存储在一个数据库中，该数据库使您可以附加到不链接到图像的对象以及该媒体文件的ID。这提供了更大的灵活性，因为将来很容易更改缩略图的大小。
* 如果更改拇指大小，则可以调整设置中所有现有拇指的大小。

屏幕截图
------------


安装Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist jinowom/yii2-filemanager "*"
```

or add

```
"jinowom/yii2-filemanager": "*"
```

to the require section of your `composer.json` file.

Apply migration
```sh
yii migrate --migrationPath=vendor/jinowom/yii2-filemanager/migrations
```

Configuration:

```php
'modules' => [
    'filemanager' => [
        'class' => 'jinowom\filemanager\Module',
        // Upload routes
        'routes' => [
            // Base absolute path to web directory
            'baseUrl' => '',
            // Base web directory url
            'basePath' => '@frontend/web',
            // Path for uploaded files in web directory
            'uploadPath' => 'uploads',
        ],
        // Thumbnails info
        'thumbs' => [
            'small' => [
                'name' => 'Мелкий',
                'size' => [100, 100],
            ],
            'medium' => [
                'name' => 'Средний',
                'size' => [300, 200],
            ],
            'large' => [
                'name' => 'Большой',
                'size' => [500, 400],
            ],
        ],
    ],
],
```
By default, thumbnails are resized in "outbound" or "fill" mode. To switch to "inset" or "fit" mode, use `mode` parameter and provide. Possible values: `outbound` (`\Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND`) or `inset` (`\Imagine\Image\ImageInterface::THUMBNAIL_INSET`):

```php
'thumbs' => [
    'small' => [
        'name' => 'Мелкий',
        'size' => [100, 100],
    ],
    'medium' => [
        'name' => 'Средний',
        'size' => [300, 200],
    ],
    'large' => [
        'name' => 'Большой',
        'size' => [500, 400],
        'mode' => \Imagine\Image\ImageInterface::THUMBNAIL_INSET
    ],
],
```

Usage
------------
Simple standalone field:

```php
use jinowom\filemanager\widgets\FileInput;

echo $form->field($model, 'original_thumbnail')->widget(FileInput::className(), [
    'buttonTag' => 'button',
    'buttonName' => 'Browse',
    'buttonOptions' => ['class' => 'btn btn-default'],
    'options' => ['class' => 'form-control'],
    // Widget template
    'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
    // Optional, if set, only this image can be selected by user
    'thumb' => 'original',
    // Optional, if set, in container will be inserted selected image
    'imageContainer' => '.img',
    // Default to FileInput::DATA_URL. This data will be inserted in input field
    'pasteData' => FileInput::DATA_URL,
    // JavaScript function, which will be called before insert file data to input.
    // Argument data contains file data.
    // data example: [alt: "Ведьма с кошкой", description: "123", url: "/uploads/2014/12/vedma-100x100.jpeg", id: "45"]
    'callbackBeforeInsert' => 'function(e, data) {
        console.log( data );
    }',
]);

echo FileInput::widget([
    'name' => 'mediafile',
    'buttonTag' => 'button',
    'buttonName' => 'Browse',
    'buttonOptions' => ['class' => 'btn btn-default'],
    'options' => ['class' => 'form-control'],
    // Widget template
    'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
    // Optional, if set, only this image can be selected by user
    'thumb' => 'original',
    // Optional, if set, in container will be inserted selected image
    'imageContainer' => '.img',
    // Default to FileInput::DATA_IDL. This data will be inserted in input field
    'pasteData' => FileInput::DATA_ID,
    // JavaScript function, which will be called before insert file data to input.
    // Argument data contains file data.
    // data example: [alt: "Ведьма с кошкой", description: "123", url: "/uploads/2014/12/vedma-100x100.jpeg", id: "45"]
    'callbackBeforeInsert' => 'function(e, data) {
        console.log( data );
    }',
]);
```

With TinyMCE:
```php
use jinowom\filemanager\widgets\TinyMCE;

<?= $form->field($model, 'content')->widget(TinyMCE::className(), [
    'clientOptions' => [
           'language' => 'ru',
        'menubar' => false,
        'height' => 500,
        'image_dimensions' => false,
        'plugins' => [
            'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code contextmenu table',
        ],
        'toolbar' => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code',
    ],
]); ?>
```

In model you must set mediafile behavior like this example:

```php
use jinowom\filemanager\behaviors\MediafileBehavior;

public function behaviors()
{
    return [
        'mediafile' => [
            'class' => MediafileBehavior::className(),
            'name' => 'post',
            'attributes' => [
                'thumbnail',
            ],
        ]
    ];
}
```

Than, you may get mediafile from your owner model.
See example:

```php
use jinowom\filemanager\models\Mediafile;

$model = Post::findOne(1);
$mediafile = Mediafile::loadOneByOwner('post', $model->id, 'thumbnail');

// Ok, we have mediafile object! Let's do something with him:
// return url for small thumbnail, for example: '/uploads/2014/12/flying-cats.jpg'
echo $mediafile->getThumbUrl('small');
// return image tag for thumbnail, for example: '<img src="/uploads/2014/12/flying-cats.jpg" alt="Летающие коты">'
echo $mediafile->getThumbImage('small'); // return url for small thumbnail
```
