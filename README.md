## YII2 Xwom System
------------
xwom是基于Yii2的综合管理业务快速开发引擎。

本引擎采用YII V2.0.38搭建，其运行环境与yii2(php>=5.6)一致。

xwom旨在为yii2爱好者提供一个基础功能稳定完善的系统，使开发者更专注于业务功能开发。xwom没有对yii2做任何的修改、封装，但是把yii2的一些优秀特性几乎都用在了xwom上， 但xwom提倡简洁、快速上手，基于xwom开发可以无需文档，可以在此基础开发其实际应用，譬如：cms内容管理系统、门户网站、商城、ERP、OA、采编、采集等。

一、更新记录Records
------------
1.0.1beta  增加4个自定义CRUD 路由，参见/backend/config/main-lock_bak.php

1.0.0beta1 开启xwom开发引擎beta1，预装常用扩展组件，对配置文件做细致的注释说明。

1.0.1 beta  已经修复kindeditor 富媒体编辑不渲染功能键的异常。

二、帮助Help
------------
（1）QQ群： 170794993  欢迎xwom爱好者，加入开发小组，加入时，请在QQ群组内申请。

（2）Email： chareler@163.com

（3）如有纰漏或不足，勿吐槽，欢迎提出建议，直接Issues 或 Email。

（4） 【码云】 同步地址 https://gitee.com/jinostart/xwom_open

三、功能
------------
 * 多语言
 * 菜单管理
 * GII 模板
 * RBAC实现管理员、角色、部门、团队、单位、应用 6个维度权限管理
 * 开启API应用
 * 计划管理
 * 流程管理WorkFlow（BPM）工作流驱动业务数据流转
 * 全栈全局设置管理
   包括：
   * 全栈基础配置
   * 全栈邮件配置
   * 全栈短信配置
   * 全栈多语言包管理
   * 全栈搜索引擎配置
   * 全栈DB引擎配置
   * 全栈数据可视化迁移
   * 全栈变量配置
   * 全栈常量配置
   * 全栈API设置
   * 系统信息
   * IP黑名单
 * 全栈日志管理
   * 全局日志
   * 短信日志
   * 行为日志
 * 开启AP子应用开发
    

四、快速体验Experience
------------
demo地址  http://xwom.womtech.cn    测试管理员账号：参见qq群 170794993 群内公告 

友情提示：数据每天、每6小时，将还原更新一次，如果您提交操作数据，如果不见了，切勿诧异，您懂的。


五、运行效果
------------

![登录](http://xwom.womtech.cn/images/demo_img/login.png)

![菜单管理](http://xwom.womtech.cn/images/demo_img/mnu.png)

![权限管理-管理员管理](http://xwom.womtech.cn/images/demo_img/rabc1.png)

![权限管理-部门、团队管理](http://xwom.womtech.cn/images/demo_img/rabc2.png)

![全栈配置管理](http://xwom.womtech.cn/images/demo_img/All_set.png)

![全栈配置管理之二](http://xwom.womtech.cn/images/demo_img/All_set2.png)


六、安装 Installation
------------
友情提示：建议php版本>=7.1

1、composer install --ignore-platform-reqs  or   composer install 

1.2、如果是由1.0.0beta1升级，请自行 composer update --ignore-platform-reqs 需要安装或升级的组件名  "版本号"   具体参见 composer.json 约定

2、依次执行以下命令初始化yii2框架以及导入数据库
```php
$ cd webApp
$ php ./init --env=Development #初始化yii2框架，线上环境请使用--env=Production
$ php ./yii migrate/up --interactive=0 #导入迁移备份数据库，执行此步骤之前请先到common/config/main-local.php修改成正确的数据库配置

```
3、配置web服务器

4、完成
附:web服务器配置
* Apache
 ```bash
<VirtualHost *>
    ########此处下面是xwom开发引擎开源地址#
    DocumentRoot path/to/xwom/backend/web/
	ServerName localhost
    #<Directory "path/to/xwom/backend/web/">
    ########此处下面是xwom开发引擎开源地址#
    <Directory "path/to/xwom/backend/web/">
    
            #SetOutputFilter DEFLATE
            #Options FollowSymLinks
            #Options Indexes FollowSymLinks Includes MultiViews ExecCGI
            Options Indexes FollowSymLinks MultiViews
            AllowOverride All
            Order allow,deny
            Allow from all
            DirectoryIndex index.html index.php

            # use mod_rewrite for pretty URL support
            RewriteEngine on
            # If a directory or a file exists, use the request directly
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            # Otherwise forward the request to index.php
            RewriteRule . index.php
    </Directory>
</VirtualHost>
  ```
  
 * Nginx
 ```bash
    略
 ```
 
七## 配置Configuration
------------
```php
common/main-local.php

    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=xxx',
            'username' => 'xxx',
            'password' => '',
            'charset' => 'utf8',
        ],
        //……
    ],


```
```php
backend/main.php

    'modules' => [
        /** migration数据库库迁移模块业务 **/
        'migration' => [
            'class' => 'migration\Module',
        ],
        //……
        /**  workflow BPM2.0**/
        'workflow' => [
            'class' => 'jinostart\workflow\manager\Module',
            'layout' => '//main',
        ],
    ],

    'components' => [
     //……
        /** workflow BPM2.0    * **/
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@backend/themes/default/views',
                    '@app/widgets' => '@backend/themes/default/widgets',
                    '@vendor/jinostart/yii2-workflow-manager/src/views' => '@backend/themes/default/modules/workflow/views'
                ],
            ],
        ],
        'workflowSource' => [
            'class' => 'jinostart\workflow\manager\components\WorkflowDbSource',
        ],
        //……
    ],


```

```php
backend/main-local.php

//Gii 集成自定义多个GURD扩展，美哒哒，可以嗷嗷的快速开发生成了……
//具体参见 git仓库中 /backend/config/main-local_bak.php 示例，可以直接拷贝修改后使用。

if (YII_ENV_DEV) {//!YII_ENV_TEST  YII_ENV_DEV  在web/index.php 入口文件配置启动
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => yii\debug\Module::className(),
        'allowedIPs' => ['::1','127.0.0.1'], //只允许本地访问gii  多个请用逗号隔开，添加自己的指定地址
    ];
     /**
      * 综合GII工具：加载 wodrowwajaxcrud 、wodrowmodel、 kartikgii-crud 、yii2 gii扩展包
      * 以下各个gii扩展工具，都有不同的特点，适合不同场景下使用。
      * 使用时可以自定义修改 templates 重定义。
      * 
    **/
    
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
    //这是kartikgii-crud。并自建一个templates：xwom_kartikgii
    $config['modules']['gii']['generators']['kartikgii-crud']= [
            'class' => \warrence\kartikgii\crud\Generator::class,
                 'templates'=> [
                     'xwom_kartikgii' => '@backend/components/gii/kartikgii/default',//自定义修改后的模板路径
                     'default' => '@vendor/warrence/yii2-kartikgii/crud/default',//这是yii2-kartikgii默认的模板路径
                 ]
        ];
    //这是yii2 自己自带的，并自建xwom、xwom_paper两个模板
    $config['modules']['gii']['generators']['crud']= [
            'class' => \backend\components\gii\crud\Generator::class,
            'templates' => [
                'xwom' => '@backend/components/gii/crud/default',//自定义修改后的xwom模板路径
                'xwom_paper' => '@backend/components/gii/crud2/default',//自定义修改后的xwom_paper模板路径
                'default' => '@vendor/yiisoft/yii2-gii/src/generators/crud/default',//这是yii 自己自带的默认的模板路径
            ]
        ];
    //这是yii controller
    $config['modules']['gii']['generators']['controller']= [
            'class' => \backend\components\gii\controller\Generator::class,
            'templates' => [
                'xwom' => '@backend/components/gii/controller/default',
                'default' => '@vendor/yiisoft/yii2-gii/src/generators/controller/default',//这是yii 自己自带的模板路径
            ]
        ];
    //这是yii model
    $config['modules']['gii']['generators']['model']= [
            'class' => 'yii\gii\generators\model\Generator',
            'baseClass'=> 'base\BaseActiveRecord',
            'templates'=> [
                'xwom'=>'@backend/components/gii/model/default',
            ]
        ];
    //这是yii form
    $config['modules']['gii']['generators']['form']= [
            'class' => 'yii\gii\generators\form\Generator',
            'templates'=> [
                'xwom'=>'@backend/components/gii/form/default',
            ]
        ];
    //这是yii extension
    $config['modules']['gii']['generators']['extension']= [
            'class' => \backend\components\gii\extension\Generator::class,
            'templates' => [
                'xwom' => '@backend/components/gii/extension/default',//自定义修改后的xwom的模板路径
                'default' => '@vendor/yiisoft/yii2-gii/src/generators/controller/default',//这是yii 自己自带的模板路径
            ]
        ];
    //这是wodrowmodel
    $config['modules']['gii']['generators']['wodrowmodel'] = [
        'class' => \wodrow\wajaxcrud\generators\model\Generator::class,
        'showName' => "YOUR MODEL Generator",
    ];
    //这是wodrowwajaxcrud，并自建一个templates：xwom_wajaxcrud
    $config['modules']['gii']['generators']['wodrowwajaxcrud'] = [
//        'class' => \wodrow\wajaxcrud\generators\crud\Generator::class,
        'class' => \backend\components\gii\yii2wajaxcrud\Generator::class,
        'showName' => "YOUR AJAX CRUD Generator",
        'templates'=> [
            'xwom_wajaxcrud' => '@backend/components/gii/yii2wajaxcrud/default',//自定义修改后的模板路径
            'default' => '@vendor/wodrow/yii2wajaxcrud/src/generators/crud/default',//这是wodrowwajaxcrud默认的模板路径
        ]
    ];
    //这是wodrowwajaxcrud，并自建一个templates：xwom_johnitvn_ajaxcrud
    //友情提示：此扩展，修改了Generator。如果报错，请自行拷贝 @backend/components/gii/johnitvn_ajaxcrud/Generator.php 覆盖vendor/johnitvn/yii2-ajaxcrud/src/generators/Generator.php
    $config['modules']['gii']['generators']['ajaxcrud'] = [
        'class' => johnitvn\ajaxcrud\generators\Generator::class,
        'showName' => "johnitvn_Ajax CRUD Generator",
        'templates'=> [
            'xwom_johnitvn_ajaxcrud' => '@backend/components/gii/johnitvn_ajaxcrud/default',//自定义修改后的模板路径
            'default' => '@vendor/johnitvn/yii2-ajaxcrud/src/generators/default',//这是wodrowwajaxcrud默认的模板路径
        ]
    ];
    
 
}

```


## 八、特别鸣谢Links
------------
- [Yii2](http://www.yiiframework.com/)
- [Yii2 Extension](http://www.yiiframework.com/extension/yii2-workflow-manager)
- 开发组成员：WeihuaWang YanchengLiu CharlesLee

九、项目说明：
------------

1、管理台项目在site1——backend（站点1）->web，m站项目在site1——backend（站点1）->wap

2、前台项目在site2——frontend（站点2）->web，m站的在site2——frontend（站点2）->wap

3、API项目在site3——API （站点3 )->web，m站的在site3——API （站点3 )->wap

4、项目的框架、外部扩展、组件基本上通过composer来管理

十、项目结构
------------
```php
common
    components/          包含公共组件、公共扩展类
    messages             多语言包（如果需要全局定义）
    config/              包含所有项目的公共配置
    extend/              需要自定义处理的第三方扩展（如果完全是第三方的扩展包，通过composer安装到vendor下面）
    javaapi/             对接java接口/php接口的类，每类接口都有父类
    models/              公共的model，基本上所有的model层都放这里，必要时在应用里的model扩展子类。mysql所有类直接放在model根目录下，规范命名。
        core/            mongo/redis/yac等核心的父类
        dbmongo/         处理mongo数据库的所有model类
        dbredis/         处理redis的所有model类
    service/             应用的公共逻辑层，按模块分目录管理
        core/            service类的父类
    tests/               测试相关
    widgets/             公共小部件

console
    config/              控制台的配置
    messages             多语言包（如果需要单独定义）
    controllers/         控制台的controller (commands)
    migrations/          数据库迁移内容
    models/              控制台的model
    runtime/             包含在运行时生成的文件

site1——backend（站点1）
    assets/              包含该应用管理JavaScript和CSS等应用程序资源的内容
    components/          包含该应用项目的组件、公共扩展类
    config/              该应用项目的配置
    controllers/         该应用项目的控制器，继承自SiteController，SiteController继承自BaseController
    external             配置的数据，如xml等
    grid                 grid小部件（如果有，就放这里）
    messages             多语言包（该应用如果需要单独定义）
    models/              空
    modules/             该应用项目的model，必要时扩展，继承公共的model来处理
        核心业务common   backend核心业务的模块，即：backend的core业务
        子应用模块app1   以子模块形式构建，该子应用的controllers、model、views独立解耦，必要时扩展，继承公共common的model来处理
        子应用模块app2   以子模块形式构建，该子应用的controllers、model、views独立解耦，必要时扩展，继承公共common的model来处理
        子应用模块app3   以子模块形式构建，该子应用的controllers、model、views独立解耦，必要时扩展，继承公共common的model来处理
        子应用模块app4   以子模块形式构建，该子应用的controllers、model、views独立解耦，必要时扩展，继承公共common的model来处理       
    runtime/             包含在运行时生成的文件
    tests/               测试相关
    views/               视图内容
    wap/                 该项目m站的入口（域名解析到此目录），以及js/css资源的管理
    web/                 该项目pc站的入口（域名解析到此目录），以及js/css资源的管理
    widgets/             该应用项目的小部件（如果有，可以放这里）

site2——frontend（站点2）
    assets/              包含该应用管理JavaScript和CSS等应用程序资源的内容
    components/          包含该应用项目的组件、公共扩展类
    messages             多语言包（该应用如果需要单独定义）
    config/              该应用项目的配置
    controllers/         该应用项目的控制器，继承自SiteController，SiteController继承自BaseController
    models/              该应用项目的model，必要时扩展，继承公共的model来处理
    runtime/             包含在运行时生成的文件
    tests/               测试相关
    views/               视图内容
    wap/                 该项目m站的入口（域名解析到此目录），以及js/css资源的管理
    web/                 该项目pc站的入口（域名解析到此目录），以及js/css资源的管理
    widgets/             该应用项目的小部件（如果有，可以放这里）

site3——API （站点3 )
    assets/              包含该应用管理JavaScript和CSS等应用程序资源的内容
    components/          包含该应用项目的组件、公共扩展类
    messages             多语言包（该应用如果需要单独定义）
    config/              该应用项目的配置
    controllers/         该应用项目的控制器，继承自SiteController，SiteController继承自BaseController
    models/              该应用项目的model，必要时扩展，继承公共的model来处理
    runtime/             包含在运行时生成的文件
    tests/               测试相关
    views/               视图内容
    web/                 该项目入口（域名解析到此目录），以及js/css资源的管理
    widgets/             该应用项目的小部件

vendor/                  包含yii2框架，扩展，组件

```

十一、一些说明
------------
```php
（一）、有关类的使用

定义的类名要注意全局唯一（针对model、service、公共方法类），类名与文件名一一对应。
model下面的类名必须唯一。
service下面分模块管理，类名必须唯一，虽然分模块管理了，不同目录不要建同名的类，以方便使用与查找。

（二）、有关函数的使用

定义方法名尽量全局唯一。使用贴近相关意义的单词全称或缩写，不用担心名称长。如果拼字有误，IDE会有提醒，有拼写错误提醒的词要调整。
函数都必须加注释，包括：函数说明、参数的数据类型与说明、返回类型说明。注意：参数注明类型、返回数据格式要前后统一。
函数要尽量小型化，考虑通用性。
（三）、有关cookie类

cookie基础类为CookiesBase，各项目（站点类型）处理cookie的在此基础上扩展，如：CookiesSite1、CookiesSite2、CookiesSite3。
对cookie的处理，都必须抽取出来放到这些处理cookie的类中来处理，针对一个cookie处理的方法成对出现，一个set，一个get。
此类函数的命名，前面是get或set，后面是对应的cookie键名有意义部分。
（四）、有关语言、翻译类

Language类，处理所有和语言、翻译有关的内容，PC、M的都整合在一起了，基本不需要怎么改了。如果原来的业务里有不在适用的，调整下业务逻辑以适用这个类的用法。

（五）、有关公共方法（添加时注意归类）

PubFun类中全是静态方法，和后端数据(缓存、数据库)没有交互。
Utils类中全是静态方法，和后端缓存数据，或配置数据有交互。另外，针对常用使用redis的，也抽取出来放到此类了，基本成对出现。
（六）、有关日志类

LogManage类，是把日志写到文件的处理类。

（七）、有关全局静态变量数据缓存的VarCache

VarCache类，处理数据对象缓存，减少在一个请求中相同数据的重复调用。如 getUserRegionFromIp() 中的使用。
使用些类时，要注意数据键名的唯一性。

（八）、有关service的约定（此说明在BaseService类中也有）

service层主要处理从controller中抽取出来的逻辑。
service层处理数据时，通过model类的方法进行处理数据。不允许调用model层父类和yii2框架内的方法处理数据，此约束为统一处理数据入口。
service层，在必要时，可对java_api接口进行进一步封装。java_api里的api，不能在controller里直接调用。

（九）、有关java_api的约定

java_api的接口对接所有的java接口，不做逻辑处理，只能数据通道。
java_api里的api，不能在controller里直接调用。可在service中再封装一下，方便处理返回的数据及异常。

（十）、有关service/api的约定

api中的类与javaapi中的类一一对应，api中的类名后面带service
api中类方法的名字，在javaapi中类的名字基础上加上api前缀作为名字，这样就容易区分或查找了。

（十一）、有关model的说明

model的父类中models/core中，父类的注释中有相关的使用说明。其中ActiveRecordModel为mysql业务的父类。

```

十二、开发中需要注意一些事项
------------
```php
在配置中，涉及数据的，统一使用中括号（[]）表示。可以的话，在代码中数组也统一使用中括号表示。
要注意IDE的错误、语法提示，在自己开发的代码中若出现要马上调整。（在IDE右上角出现绿色的勾，说明此文件已达到最规范的格式）
统一注释格式。包括：函数说明、参数的数据类型与说明、返回类型说明。注意：参数注明类型、返回数据格式要前后统一。
在循环中不能出现循环调用数据库的情况，除非在非循环下不能实现相关业务（此时要提出来讨论）。注意：这些情况在新项目已改写，在对接业务时要注意。
对于php的代码，写完代码定型后，用IDE对代码和注释进行格式化，以保持格式统一。
对于配置的管理，对于同类配置放在一个数组中，不要平行配置，以方便集中管理。
BaseService、BaseController类中，$_baseUrl、$_suffix、$_siteId、$_terminalType 为全局使用的变量。在代码中直接使用，不要再使用Yii::$app->params取配置。这些变量不能再定义作其他作用。
前端传值，对于多类型判断尽量传字符串，后端再映射对应值。
常用参数说明
1、Yii::$app->request->hostInfo 返回 http://www.example.com, url中host info部分

2、Yii::$app->request->serverName 返回 www.example.com, URL中的host name

日志格式说明
自定义日志：

1、自定义日志类为 LogManage，统一格式，格式用json，方便以后使用ELK分析

日志格式（json）
ver                日志版本号
serTime            写日志时间，也相当于日志上报时间（上报ELK时间）
LogApp             应用的类型，如nimini的pc的日志：app-nimini-pc  Yii::$app->id . '-' . YII_SITE_TYPE
logType            日志类型（0：能用类型  1：错误日志  2：性能日志）
logLevel           日志等级（0：不写日志 1：写所有日志内容 2：写日志但不写retData的内容）
logModule          日志模块，目前都为0
userId             用户ID，如果用户没登录则值为0
content            日志详细内容
    processTime    请求响应时长，以毫秒为单位
    serverAddr     服务器IP地址，$_SERVER['SERVER_ADDR']
    method         请求方法，$_SERVER['REQUEST_METHOD']，或 Yii::$app->request->getMethod()
    userAgent      客户端浏览器类型，Yii::$app->request->getUserAgent()
    userHost       客户机的host，Yii::$app->request->getUserHost()
    userIp         客户机的IP地址，Yii::$app->request->getUserIp()
    requestData    日志类型，默认为0，若有请求和响应则：1是请求，2是响应
    requestData    请求参数，$_REQUEST
    requestUrl     请求URL
    retCode        返回码
    retMsg         返回错误具体信息
    retData        返回具体数据

```


