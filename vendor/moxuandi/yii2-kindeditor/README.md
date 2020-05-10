[富文本编辑器 KindEditor for Yii2](http://kindeditor.net)
================
KindEditor 是一套开源的在线HTML编辑器，主要用于让用户在网站上获得所见即所得编辑效果，开发人员可以用 KindEditor 把传统的多行文本输入框(textarea)替换为可视化的富文本输入框。
KindEditor 使用 JavaScript 编写，可以无缝地与 Java、.NET、PHP、ASP 等程序集成，比较适合在 CMS、商城、论坛、博客、Wiki、电子邮件等互联网应用上使用。


安装:
------------
使用 [composer](http://getcomposer.org/download/) 下载:
```
# 2.2.x(yii >= 2.0.24):
composer require moxuandi/yii2-kindeditor:"~2.2.0"

# 2.x(yii >= 2.0.16):
composer require moxuandi/yii2-kindeditor:"~2.1.0"
composer require moxuandi/yii2-kindeditor:"~2.0.0"

# 1.x(非重要Bug, 不再更新):
composer require moxuandi/yii2-kindeditor:"~1.0"

# 旧版归档(不再更新):
composer require moxuandi/yii2-kindeditor:"~0.1"

# 开发版:
composer require moxuandi/yii2-kindeditor:"dev-master"
```


使用:
-----
在`Controller`中添加:
```php
public function actions()
{
    return [
        'Kupload' => [
            'class' => 'moxuandi\kindeditor\UploaderAction',
            //可选参数, 参考 config.php
            'config' => [
                'imageAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],  // 允许上传的文件类型
                'imagePathFormat' => '/uploads/image/{yyyy}{mm}{dd}/{hh}{ii}{ss}_{rand:6}',  // 文件保存路径
                'modelClass' => 'common\model\Upload',  // 文件信息是否保存入库
                'process' => [  // 二维数组, 将按照子数组的顺序对图片进行处理
                    'match' => ['image', 'process'],  // 图片处理后保存路径的替换规则, 必须是两个元素的数组
                    'thumb' => [  // 缩略图配置
                        'width' => 300,  // 缩略图宽度
                        'height' => 200,  // 缩略图高度
                        'mode' => 'outbound',  // 生成缩略图的模式, 可用值: 'inset'(补白), 'outbound'(裁剪, 默认值)
                    ],
                    'crop' => [  // 裁剪图配置
                        'width' => 300,  // 裁剪图的宽度
                        'height' => 200,  // 裁剪图的高度
                        'top' => 200,  // 裁剪图顶部的偏移, y轴起点, 默认为`0`
                        'left' => 200,  // 裁剪图左侧的偏移, x轴起点, 默认为`0`
                    ],
                    'frame' => [  // 添加边框的配置
                        'margin' => 20,  // 边框的宽度, 默认为`20`
                        'color' => '666',  // 边框的颜色, 十六进制颜色编码, 可以不带`#`, 默认为`666`
                        'alpha' => 100,  // 边框的透明度, 可能仅`png`图片生效, 默认为`100`
                    ],
                    'watermark' => [  // 添加图片水印的配置
                        'watermarkImage' => '/uploads/watermark.png',  // 水印图片的绝对路径
                        'top' => 100,  // 水印图片的顶部距离原图顶部的偏移, y轴起点, 默认为`0`
                        'left' => 200,  // 水印图片的左侧距离原图左侧的偏移, x轴起点, 默认为`0`
                    ],
                    'text' => [  // 添加文字水印的配置
                        'text' => 'TONGMENGCMS',  // 水印文字的内容
                        'fontFile' => '@yii/captcha/SpicyRice.ttf',  // 字体文件, 可以是绝对路径或别名
                        'top' => 100,  // 水印文字距离原图顶部的偏移, y轴起点, 默认为`0`
                        'left' => 200,  // 水印文字距离原图左侧的偏移, x轴起点, 默认为`0`
                        'fontOptions' => [  // 字体属性
                            'size' => 12,  // 字体的大小, 单位像素(`px`), 默认为`12`
                            'color' => 'fff',  // 字体的颜色, 十六进制颜色编码, 可以不带`#`, 默认为`fff`
                            'angle' => 0,  // 写入文本的角度, 默认为`0`
                        ],
                    ],
                    'resize' => [  // 调整图片大小的配置
                        'width' => 300,  // 图片调整后的宽度
                        'height' => 200,  // 图片调整后的高度
                        'keepAspectRatio' => true,  // 是否保持图片纵横比, 默认为`true`
                        'allowUpscaling' => false,  // 如果原图很小, 图片是否放大, 默认为`false`
                    ],
                ],

                // 如果`uploads`目录与当前应用的入口文件不在同一个目录, 必须做如下配置:
                'rootPath' => dirname(dirname(Yii::$app->request->scriptFile)),
                'rootUrl' => 'http://image.advanced.ccc',
            ],
        ],
    ];
}
```

在`View`中添加:
```php
1. 简单调用:
$form->field($model, 'content')->widget('moxuandi\kindeditor\KindEditor');

2. 带参数调用:
$form->field($model, 'content')->widget('moxuandi\kindeditor\KindEditor',[
    'editorOptions' => ['width' => '1000', 'height' => 500],
]);

3. 不带 $model 调用:
\moxuandi\kindeditor\KindEditor::widget([
    'name' => 'image',
    'editorOptions' => ['width' => '1000', 'height' => 500],
]);
```

编辑器相关配置，请在`view`中配置，参数为`editorOptions`，比如定制菜单，编辑器大小等等，具体参数请查看[KindEditor官网文档](http://kindeditor.net/docs/option.html)


#### 单独调用插件:
```php
$form->field($model, 'imgurl')->widget('moxuandi\kindeditor\KindEditor', [
    'editorType' => 'imageDialog',
    'options' => [  // input输入域的html属性
        'class' => 'form-control',
        'style' => 'display:inline-block;width:calc(100% - 84px);margin-right:6px;',
    ],
    'buttonOptions' => [  // 按钮的html属性
        'class' => 'btn btn-default',
    ],
]);

\moxuandi\kindeditor\KindEditor::widget([
    'name' => 'image',
    'editorType' => 'imageDialog',
    'options' => [  // input输入域的html属性
        'class' => 'form-control',
        'style' => 'display:inline-block;width:calc(100% - 84px);margin-right:6px;'
    ],
    'buttonOptions' => [  // 按钮的html属性
        'class' => 'btn btn-default'
    ],
]);
```

#### 同时调用编辑器和独立插件，并且图片/文件上传不一样时：
```php
Controller:
public function actions()
{
    return [
        'Kupload' => [
            'class' => 'moxuandi\kindeditor\UploaderAction',
        ],
        'Kupload2' => [
            'class' => 'moxuandi\kindeditor\UploaderAction',
            //可选参数, 参考 config.php
            'config' => [
                'process' => [
                    // 生成缩略图
                    'thumb' => [
                        'width' => 150,  // 缩略图宽度
                        'height' => 100, // 缩略图高度
                    ],
                ],
            ],
        ],
        'Kupload3' => [
            'class' => 'moxuandi\kindeditor\UploaderAction',
            //可选参数, 参考 config.php
            'config' => [
                'filePathFormat' => '/uploads/file/{yyyy}{mm}{dd}/{hh}{ii}{ss}_{rand:6}',  // 文件保存路径
                'fileRootPath' => '/uploads/file/',  // 浏览服务器时的根目录
            ],
        ],
    ];
}


view1. 编辑器(不生成缩略图):
$form->field($model, 'content')->widget('moxuandi\kindeditor\KindEditor');

view2. 图片上传(生成缩略图):
$form->field($model, 'imgurl')->widget('moxuandi\kindeditor\KindEditor', [
    'editorType' => 'imageDialog',
    'editorOptions' => [
        'uploadJson' => Url::to(['Kupload2', 'action'=>'uploadJson']),  // 指定上传文件的服务器端程序
        'fileManagerJson' => Url::to(['Kupload2', 'action'=>'fileManagerJson']),  // 指定浏览远程图片的服务器端程序
    ],
]);

view3. 文件上传:
$form->field($model, 'imgurl')->widget('moxuandi\kindeditor\KindEditor', [
    'editorType' => 'fileDialog',
    'editorOptions' => [
        'uploadJson' => Url::to(['Kupload3', 'action'=>'uploadJson']),  // 指定上传文件的服务器端程序
        'fileManagerJson' => Url::to(['Kupload3', 'action'=>'fileManagerJson']),  // 指定浏览远程图片的服务器端程序
    ],
]);
```

editorType: 定义编辑器的类型, 值有：
```php
     textEditor: HTML编辑器(默认)
     colorPicker: 取色器
     uploadButton: 自定义上传按钮
     fileDialog: 上传文件
     imageDialog: 上传图片(网络图片 + 本地上传)
     RemoteImageDialog: 上传图片(网络图片)
     LocalImageDialog: 上传图片(本地上传)
     imageManager: 浏览服务器(图片)
     flashManager: 浏览服务器(Flash)
     mediaManager: 浏览服务器(视音频)
     fileManager: 浏览服务器(文件)
     multiImageDialog: 批量上传图片(未实现)
```

## 可视化图片上传:
```
$form->field($model, 'imgurl')->widget('moxuandi\kindeditor\KindEditorImage', [
    'editorType' => 'imageDialog',
]);

\moxuandi\kindeditor\KindEditorImage::widget([
    'name' => 'image',
    'value' => '/uploads/image/20190216/111632_184320.jpg',
    'editorType' => 'remoteImageDialog',
]);
```
