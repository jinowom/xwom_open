<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',//应用id，必须唯一
    'name' => 'xwom系统',//设置默认系统站点信息常量 引用方法：Yii::$app->name；
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',//控制器命名空间
    'defaultRoute' => 'index/index',// 后台默认控制器
    'language' => 'zh-CN',// 后台默认语言
    'sourceLanguage' => 'zh-CN',//后台资源默认语言 zh-CN  en-US
    'timeZone' => 'Asia/Shanghai',//后台默认时区
    'bootstrap' => ['log'],//['debug']//打开日志或调试debug
    'aliases' => [
        '@migration' => '@backend/modules/migration',//定义@migration命名空间别名
    ],
    /*
     * 调试开发时，加载模块，请在在main-local.php里自定义配资；开发完毕再 添加到下面“固化”
     */
    'modules' => [
        /** ------ 加载公用模块业务 ------ **/
        'common' => [
            'class' => 'backend\modules\common\Index',
        ],
        /**------ 加载migration数据库库迁移模块业务 ------ **/
        'migration' => [
            'class' => 'migration\Module',
        ],
        /**  workflow BPM2.0**/
        'workflow' => [
            'class' => 'jinostart\workflow\manager\Module',
            'layout' => '//main',
        ],
        /**加载yii2-kartikgii 的模块**/
        'gridview' => [
            'class' => 'kartik\grid\Module',
        ],
        /**task任务管理模块业务**/
        'task' => [
            'class' => 'backend\modules\task\Module',
        ],
        /**yii2-round-switch-column扩展，实现字段的开关**/
        'roundSwitch' => [
            'class' => 'nickdenry\grid\toggle\Module',
        ],
        /**------ 加载xe子系统模块业务 ------ **/

        /**------ 加载xp子系统模块业务 ------ **/

        /**------ 加载xpo子系统模块业务 ------     */

        /**------ 加载wiki单页面模块业务 ------ **/

        /**------ 系统模块 ------ **/
        
        /**------ 微信模块 ------ **/
        
        /**------ 会员模块 ------ **/
        
        /**------ oauth2 ------ **/
        
        /**------ filemanager 附件文件管理配置 ------ **/
        'filemanager' => [
            'class' => 'pendalf89\filemanager\Module',
            // Upload routes
            'routes' => [
                // Base absolute path to web directory
                'baseUrl' => '',
                // Base web directory url
                'basePath' => '@backend/web',
                // Path for uploaded files in web directory
                'uploadPath' => 'uploads',
            ],
            // Thumbnails info 缩略图配置
            'thumbs' => [
                'small' => [
                    'name' => 'samll',
                    'size' => [100, 100],
                ],
                'medium' => [
                    'name' => 'medium',
                    'size' => [300, 200],
                ],
                'large' => [
                    'name' => 'large',
                    'size' => [500, 400],
                ],
            ],
            ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrfBackend',
            'cookieValidationKey' => '_backend',
            "enableCsrfValidation" => false,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,//true false 
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],//true false 
            'loginUrl' => ['site/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
            'timeout' => 8640,//session过期时间，单位为秒
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    //'logFile' => '@runtime/logs/' . date('Y-m/d') . '.log',//设置log存贮地址
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authManager' => [
            'class' => 'common\models\AdminManager',
            // uncomment if you want to cache RBAC items hierarchy
            // 'cache' => 'cache',
            'itemTable' => '{{%auth_item}}',
            'itemChildTable' => '{{%auth_item_child}}',
            'assignmentTable' => '{{%auth_assignment}}',
            'ruleTable' => '{{%auth_rule}}',
            //'defaultRoles' => [''],
        ],
        /**
         * 美化路由配置
         */
        
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,// false 隐藏入口文件index.php  true 美化路由(注:需要配合web服务器配置伪静态，详见xxx), false 不美化路由
            'enableStrictParsing' => false,// true 启动url严格匹配模式，匹配规则 yii\web\UrlManager::rules
            'showScriptName' => false,//true 为隐藏index.php
            //'suffix'=>'.html',//添加后缀名 伪静态
            'rules' => [
            ],
        ],
        */
        
        /*
         * 国际化语言配置使用，这里的设置的关键在于两个语言设置，即 sourceLanguage（源语言） 和 language（目标语言） 的设置，
         * 该翻译服务就是将网站从源语言翻译成目标语言的实现，其中目标语言是可以随时更改的    
         */
        
        'i18n' => [
            'translations' => [//多语言包设置
                'workflow' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@app/messages'
                ],
                '*' => [
                    'class' => yii\i18n\PhpMessageSource::className(),
                    'basePath' => '@backend/messages',//定义目标语言类的路径
                    'sourceLanguage' => 'en-US',//zh-CN en-US
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],

                
            ],
        ],
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
//        'workflowBehaviorAttach' => \backend\components\WorkflowBehaviorAttach::className(),
        'workflowSource' => [
            'class' => 'jinostart\workflow\manager\components\WorkflowDbSource',
        ],
        /*
         * 注册assetManager资源文件
         */
        
        'assetManager' => [
            'appendTimestamp' => true,// 设置 true 存在浏览器缓存问题，在开发阶段可以通过配置来避免这个问题，尤其是开发移动端页面的时候特别有用.
            'linkAssets' => false,//若为unix like系统这里可以修改成true则创建css js文件软链接到assets而不是拷贝css js到assets目录
            'bundles' => [
                /*
                 * 场景：譬如 Yii2中使用了 AdminLTE 3.0.0  后框架自带的bootstrap.css 与 admin样式有冲突,需要去掉 bootstrap.css，禁止加载某资源的方法
                 * 就可以使用如下方法禁止加载
                 * 'yii\bootstrap\BootstrapAsset' => false,//禁用 加载BootstrapAsset资源
                 * 或者
                 *'yii\bootstrap\BootstrapAsset' => [
                 *       'css' => [],   // 禁用 bootstrap.css
                 *       'sourcePath' => null,  // 防止在 frontend/web/asset 下生产文件
                 *   ],
                 *   'yii\bootstrap\BootstrapPluginAsset' => [
                 *       'js' => [],  // 去除 bootstrap.js
                 *       'sourcePath' => null,  // 防止在 frontend/web/asset 下生产文件
                 *   ],
                 * 
                 */
                backend\assets\AppAsset::className() => [
                    'sourcePath' => '@backend/web/',
                    'css' => [
                        'a' => 'css/font.css',
                        'b' => 'css/xadmin.css',
//                        'c' => 'css/bootstrap.css',

                    ],
                    'js' => [
                        'a' => 'js/jquery.min.js',
                        'b' => 'lib/layui/layui.js',
                        'c' => 'js/common.js',
                        'd' => 'js/xadmin.js',

                    ],
                ],
                 

            ]
        ],
        
    ],
    /*
     * 利用controllerMap自定义控制器类
     * 示例：
         'controllerMap' => [
        'api'=>[  
            'class'=>'frontend\api\ApiController' // xxx控制器 
        ]],  
    ],
     */
    
    'params' => $params,
];
