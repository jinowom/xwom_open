yii2-helpers: 助手类,通用上传类
==================

> 提示: 2.2.x版本不再自动引入依赖的扩展！如果使用以下类, 请引入相关的扩展


## 目录

1. [Helper助手类用法示例](#Helper助手类用法示例)

2. [Uploader上传类用法示例](#Uploader上传类用法示例): 需引入扩展[yiisoft/yii2-imagine](https://github.com/yiisoft/yii2-imagine)

3. [OAuth2第三方登录用法示例](#OAuth2第三方登录用法示例): 需引入扩展[yiisoft/yii2-authclient](https://github.com/yiisoft/yii2-authclient)


## 安装:
使用 [composer](http://getcomposer.org/download/) 下载:
```
# 2.2.x(yii >= 2.0.24):
composer require moxuandi/yii2-helpers:"~2.2.0"

# 2.x(yii >= 2.0.16):
composer require moxuandi/yii2-helpers:"~2.1.0"
composer require moxuandi/yii2-helpers:"~2.0.0"

# 1.x(非重要Bug, 不再更新):
composer require moxuandi/yii2-helpers:"~1.0"

# 旧版归档(不再更新):
composer require moxuandi/yii2-helpers:"~0.1"

# 开发版:
composer require moxuandi/yii2-helpers:"dev-master"
```


### Helper助手类用法示例:
```php
// 判断当前服务器操作系统, eg: 'Linux'或'Windows':
echo Helper::getOs();

// 获取当前微妙数, eg: 1512001416.3352:
echo Helper::microTimeFloat();

// 格式化文件大小, eg: '1.46 MB'.
echo Helper::byteFormat(1532684);

// 获取图片的宽高等属性, eg: ['width' => 1366, 'height' => 768, 'type' => 'png', 'mime' => 'image/png', 'attr' => 'width="203" height="50"']:
echo Helper::getImageInfo('uploads/img.png');

// 获取文件的扩展名, eg: 'jpg':
echo Helper::getExtension('uploads/img.jpg');

// 获取指定格式的文件路径, eg: 'uploads/image/201707/1512001416.jpg':
echo Helper::getFullName('img.jpg', 'uploads/image/{yyyy}{mm}/{time}');
```

### Uploader上传类用法示例:
```php
$config = [
    'allowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],  // 上传图片格式显示
    'pathFormat' => 'uploads/image/{yyyy}{mm}/{yy}{mm}{dd}_{hh}{ii}{ss}_{rand:4}',  // 上传保存路径, 可以自定义保存路径和文件名格式
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
];
$up = new Uploader('upfile', $config);
echo Json::encode([
    'url' => $up->fullName,
    'state' => Uploader::$stateMap[$up->status]
]);
```

> 提示: 如果`process.resize`的宽高都未设置, 将使用图片的原始宽高; 此时, 可能会压缩图片大小, 但也有可能造成图片大小增大!!!

> 提示: 缩略图配置中, `width`和`height`其中一个可以设置为`null`, 此时将按原图比例自动缩放图片. 但不能同时为`null`!

> 提示: 配置中的`match`参数, 当两个元素的值相同时, 将不会保存原图, 而仅保留缩略图.

> 如果要使用文件保存入库功能, 必须将`modelClass`设置为类的完全限定名称(eg: `common\model\Upload`), 该类必须是`yii\db\ActiveRecord`的子类, 且必须包含一些字段(参考`@vendor\moxuandi\yii2-helpers\migrations\m190101_010101_upload.php`)

> 迁移命令: `yii migrate --migrationPath=@vendor/moxuandi/yii2-helpers/migrations`

### OAuth2第三方登录用法示例

> 参考[AuthClient 扩展](https://github.com/yiisoft/yii2-authclient/tree/master/docs/guide-zh-CN):

#### 0. `yii2-authclient`默认提供了以下几个立即可用的客户端:
- [yii\authclient\clients\Facebook](https://github.com/yiisoft/yii2-authclient/blob/master/src/clients/Facebook.php)
- [yii\authclient\clients\GitHub](https://github.com/yiisoft/yii2-authclient/blob/master/src/clients/GitHub.php)
- [yii\authclient\clients\Google](https://github.com/yiisoft/yii2-authclient/blob/master/src/clients/Google.php)
- [yii\authclient\clients\GoogleHybrid](https://github.com/yiisoft/yii2-authclient/blob/master/src/clients/GoogleHybrid.php)
- [yii\authclient\clients\LinkedIn](https://github.com/yiisoft/yii2-authclient/blob/master/src/clients/LinkedIn.php)
- [yii\authclient\clients\Live](https://github.com/yiisoft/yii2-authclient/blob/master/src/clients/Live.php)
- [yii\authclient\clients\Twitter](https://github.com/yiisoft/yii2-authclient/blob/master/src/clients/Twitter.php)
- [yii\authclient\clients\TwitterOAuth2](https://github.com/yiisoft/yii2-authclient/blob/master/src/clients/TwitterOAuth2.php)
- [yii\authclient\clients\VKontakte](https://github.com/yiisoft/yii2-authclient/blob/master/src/clients/VKontakte.php)
- [yii\authclient\clients\Yandex](https://github.com/yiisoft/yii2-authclient/blob/master/src/clients/Yandex.php)

#### 1. 配置应用程序
```php
'components' => [
    'authClientCollection' => [
        'class' => 'yii\authclient\Collection',
        'clients' => [
            'qq' => [
                'class' => 'moxuandi\oauth\QQClient',
                'clientId' => 'your APP ID',
                'clientSecret' => 'your APP Key',
            ],
            'github' => [
                'class' => 'yii\authclient\clients\GitHub',
                'clientId' => 'your Client ID',
                'clientSecret' => 'your Client Secret',
            ],
        ]
    ],
],
```

#### 2. 向控制器添加动作
参考:[向控制器中添加动作](https://github.com/yiisoft/yii2-authclient/blob/master/docs/guide-zh-CN/quick-start.md#%E5%90%91%E6%8E%A7%E5%88%B6%E5%99%A8%E4%B8%AD%E6%B7%BB%E5%8A%A0%E5%8A%A8%E4%BD%9C)
```php
class SiteController extends Controller
{
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess(BaseClient $client)
    {
        $attributes = $client->getUserAttributes();
    
        // user login or signup comes here(处理登录)
    }
}
```

#### 3. 向登录视图添加小部件:
```html
<?= \yii\authclient\widgets\AuthChoice::widget([
    'baseAuthUrl' => ['/site/auth'],
    'popupMode' => false,  // 不使用弹出窗口
]) ?>
```
或
```html
<a class="auth-link" href="<?= Url::to(['/site/auth', 'authclient' => 'qq']) ?>">QQ 登录</a>
<a class="auth-link" href="<?= Url::to(['/site/auth', 'authclient' => 'github']) ?>">Github 登录</a>
```

#### 4. 保存授权数据的表结构
```sql
CREATE TABLE IF NOT EXISTS `auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `source` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `source_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk-auth-user_id-user-id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```
```php
$mg = new Migration();
$mg->createTable('auth', [
    'id' => $mg->primaryKey(),
    'user_id' => $mg->integer()->notNull(),
    'source' => $mg->string()->notNull(),  // 验证提供商: qq/weibo/wechat
    'source_id' => $mg->string()->notNull(),  // 唯一ID: openid
]);
$mg->addForeignKey('fk-auth-user_id-user-id', 'auth', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
```

#### 5. 申请:
- QQ第三方登录申请: [QQ互联管理中心](https://connect.qq.com/manage.html#/)
- 微信第三方登录申请: [微信开发平台](https://open.weixin.qq.com/)
- 新浪微博第三方登录申请: [新浪微博开发平台](http://open.weibo.com/)
- Github第三方登录申请: [Developer settings](https://github.com/settings/applications/551810)
