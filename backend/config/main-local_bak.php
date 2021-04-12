<?php
$config = [
    //'language' => 'zh-CN',//默认语言
    'timeZone' => 'Asia/Shanghai',//backend后台默认时区
    'sourceLanguage' => 'en-US',//backend后台默认资源语言
    'components' => [
        //加载yii2-swiftmailer扩展
         'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,//false or true
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp address',//主机地址
                'username' => 'your email username',//发送邮箱的名称
                'password' => 'password',//密码
                'port' => '25',//端口
                'encryption' => 'tls',
            ],
            'messageConfig'=>[  
               'charset'=>'UTF-8',  
               'from'=>['your email username' => 'your app name']  
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
            'cookieValidationKey' => 'da0fh3Dgx0ZGEiLTPQB_4BJakLRRCpLM_xwom_backend_xp',
            "enableCsrfValidation" => false,
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'xwom_backend_xp',//session 名称
            'timeout' => 8640,//设置过期时间
        ],
        /**
         * 美化路由配置
         */
    ],
 
];

if (YII_ENV_DEV) {//!YII_ENV_TEST  YII_ENV_DEV  在web/index.php 入口文件配置启动
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => yii\debug\Module::className(),
        //'allowedIPs' => ['127.0.0.1'],
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
        'allowedIPs' => ['::1','127.0.0.1','127.0.0.148'], //只允许本地访问gii  用逗号隔开，添加自己的指定地址
    ];

    //这是yii controller
    $config['modules']['gii']['generators']['controller']= [
            'class' => \backend\components\gii\controller\Generator::class,
            'showName' => "Yii2默认 controller 模型",
            'templates' => [
                'xwom' => '@backend/components/gii/controller/default',
                'default' => '@vendor/yiisoft/yii2-gii/src/generators/controller/default',//这是yii 自己自带的模板路径
            ]
        ];
    //这是yii model
    $config['modules']['gii']['generators']['model']= [
            //'class' => 'yii\gii\generators\model\Generator',
            'class' => \backend\components\gii\model\Generator::class,
            'baseClass'=> 'base\BaseActiveRecord',
            'showName' => "Yii2默认 model 模型",
            'templates'=> [
                'xwom'=>'@backend/components/gii/model/default',
            ]
        ];
    //这是yii form
    $config['modules']['gii']['generators']['form']= [
            //'class' => 'yii\gii\generators\form\Generator',
            'class' => \backend\components\gii\form\Generator::class,
            'showName' => "Yii2默认 form 模型",
            'templates'=> [
                'xwom'=>'@backend/components/gii/form/default',
            ]
        ];
    //这是yii Module
    $config['modules']['gii']['generators']['module']= [
            //'class' => 'yii\gii\generators\form\Generator',
            'class' => \backend\components\gii\module\Generator::class,
            'showName' => "Yii2默认 module 模型",
            'templates'=> [
                'xwom'=>'@backend/components/gii/module/default',
            ]
        ];
    //这是yii extension
    $config['modules']['gii']['generators']['extension']= [
            'class' => \backend\components\gii\extension\Generator::class,
            'showName' => "Yii2默认 Extension 模型",
            'templates' => [
                'xwom' => '@backend/components/gii/extension/default',//自定义修改后的xwom的模板路径
                'default' => '@vendor/yiisoft/yii2-gii/src/generators/controller/default',//这是yii 自己自带的模板路径
            ]
        ];
    //这是wodrow-model
    $config['modules']['gii']['generators']['wodrowmodel'] = [
        'class' => \wodrow\wajaxcrud\generators\model\Generator::class,
        'showName' => "自定义MODEL模型：Xwom MODEL Generator",
    ];
    //这是yii2 自己自带的，并自建xwom、xwom_paper两个模板
    $config['modules']['gii']['generators']['crud']= [
            'class' => \backend\components\gii\crud\Generator::class,
            'showName' => "YII2默认CRUD模板：CRUD Generator",
            'templates' => [
                'xwom' => '@backend/components/gii/crud/default',//自定义修改后的xwom模板路径
                'xwom_paper' => '@backend/components/gii/crud2/default',//自定义修改后的xwom_paper模板路径
                'default' => '@vendor/yiisoft/yii2-gii/src/generators/crud/default',//这是yii 自己自带的默认的模板路径
            ]
        ];
    //这是wodrow-wajaxcrud，并自建一个templates：xwom_wajaxcrud
    $config['modules']['gii']['generators']['wodrowwajaxcrud'] = [
//        'class' => \wodrow\wajaxcrud\generators\crud\Generator::class,//原模板的扩类
        'class' => \backend\components\gii\yii2wajaxcrud\Generator::class,
        'showName' => "CRUD模板一：YOUR AJAX CRUD Generator",
        'templates'=> [
            'xwom_wajaxcrud' => '@backend/components/gii/yii2wajaxcrud/default',//自定义修改后的模板路径
            'default' => '@vendor/wodrow/yii2wajaxcrud/src/generators/crud/default',//这是wodrowwajaxcrud默认的模板路径
        ]
    ];
    //这是wodrowwajaxcrud，并自建一个templates：xwom_johnitvn_ajaxcrud
    $config['modules']['gii']['generators']['ajaxcrud'] = [
        //'class' => johnitvn\ajaxcrud\generators\Generator::class,//原模板的扩展类
        'class' => \backend\components\gii\yii2_ajaxcrud\Generator::class,//自定义模板扩展类
        'showName' => "CRUD模板二：johnitvn_Ajax CRUD Generator",
        'templates'=> [
            'xwom_johnitvn_ajaxcrud' => '@backend/components/gii/johnitvn_ajaxcrud/default',//自定义修改后的模板路径
            'default' => '@vendor/johnitvn/yii2-ajaxcrud/src/generators/default',//这是wodrowwajaxcrud默认的模板路径
        ]
    ];
    //这是kartikgii-crud。并自建一个templates：xwom_kartikgii
    $config['modules']['gii']['generators']['kartikgii-crud']= [
            //'class' => \warrence\kartikgii\crud\Generator::class,
            'class' => \backend\components\gii\kartikgii\Generator::class,
            'showName' => "CRUD模板三：YOUR AJAX CRUD Generator",
                 'templates'=> [
                     'xwom_kartikgii' => '@backend/components/gii/kartikgii/default',//自定义修改后的模板路径
                     'default' => '@vendor/warrence/yii2-kartikgii/crud/default',//这是yii2-kartikgii默认的模板路径
                 ]
        ];
    //这是layui-crud。并自建一个templates：xwom_layuigii
    $config['modules']['gii']['generators']['layuigii-crud']= [
            //'class' => \jinowom\Layuigii\crud\Generator::class,
            'class' => \backend\components\gii\layui_gii\src\crud\Generator::class,
            'showName' => "CRUD模板四：Layui CRUD Generator",
                 'templates'=> [
                    //模板名 => 模板路径
                    'xwom_layuiCrud' => '@backend/components/gii/layui_gii/src/crud/default',//自定义修改后的模板路径 
                    'default' => '@vendor/jinowom/layui-gii/src/crud/default',//这是layuigii默认的模板路径
                 ]
        ];
    //这是layui-model
    $config['modules']['gii']['generators']['layuigii-model']= [
            'class' => \backend\components\gii\layui_gii\src\model\Generator::class,
            'baseClass'=> 'base\BaseActiveRecord',
            'showName' => "layui-model 模型",
            'templates'=> [
                'xwom'=>'@backend/components/gii/layui_gii/src/model/default',
            ]
        ];
    //这是layui-API controller。并自建一个templates：xwom_layuigii
    $config['modules']['gii']['generators']['layuigii-controller']= [
            'class' => \backend\components\gii\layui_gii\src\api\Generator::class,
            'showName' => "Layui_API：Controller Generator",
                 'templates'=> [
                    //模板名 => 模板路径
                    'xwom_layuiCrud' => '@backend/components/gii/layui_gii/src/api',//自定义修改后的模板路径 
                 ]
        ];
 
}
return $config;


