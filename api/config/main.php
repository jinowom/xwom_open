<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',//控制器命名空间
//    'defaultRoute' => 'index/index',// Xadp system(API)默认控制器
    'language' => 'zh-CN',// Xadp system(API)默认语言
    'sourceLanguage' => 'zh-CN',//Xadp system(API)资源默认语言 zh-CN  en-US
    'timeZone' => 'Asia/Shanghai',//Xadp system(API)默认时区
    'bootstrap' => ['log'],
    //添加模块v1和v2，分别表示不同的版本
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module'
        ],
        'v2' => [
            'class' => 'api\modules\v2\Module'
        ],
        /**------ yii2的restful接口API生成在线文档管理配置 
         * 此处也可加载到 api/config/main.php 或 /backend/config/main.php或common/config/main.php
         * **/        
        'xadpapi'=>[//xwomapi是module的名字,这里自定义
            'class'=> jinowom\api\Module::class,//'class'=>'jinowom\api\Module',
            'apiConfig'=>require(__DIR__ . '/../../vendor/jinowom/yii2-api/demo/apiConfig.php'), //扩展包的apiConfig配置页面地址  		
            //'apiConfig'=>require(__DIR__ . '/../../api/modules/v1/apiConfig.php'),//引用xadp system(API)应用的apiConfig配置页面地址		
        ],
    ],
    'controllerNamespace' => 'api\controllers',
    'components' => [
        'request' => [
            'enableCookieValidation' => false,   //关闭yii自带的防止csrf攻击属性
            'enableCsrfValidation'   => false,
            'csrfParam' => '_csrf-api',
            'cookieValidationKey' => '_api',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'enableSession' => false,  // API ++
            'loginUrl' => null // API ++
//            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
        ],
        //状态码
        'response' => [
            'format' => 'json',//返回json格式
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->isSuccessful){
                    $response->data = [
                        'code' => $response->data['code'],
                        'message' => $response->data['message'],
                        'data' => $response->data['data'],
                    ];
                } else {
                    if ($response->data['code'] != 0){
                        $response->data = [
                            'code' => $response->data['code'],
                            'message' => $response->data['message'],
                            'data' => [],
                        ];
                    } else {
                        $response->data = [
                            'code' => 404,
                            'message' => '页面未找到',
                            'data' => [],
                        ];
                    }
                }
            },
        ],
        /*'session' => [
            // this is the name of the session cookie used for login on the api
            'name' => 'advanced-api',
        ],*/
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,//false
            'rules' => [
                //代码版本号
                ['class'         => 'yii\rest\UrlRule',
                 'controller'    => 'version',
                 'pluralize'     => false,  //不在url链接中的project-team后加s 复数
                 'extraPatterns' => [
                     'GET index'       => 'index',//获取最新的版本号
                 ],
                ],

                //app版本号
                ['class'         => 'yii\rest\UrlRule',
                 'controller'    => 'app-version',
                 'pluralize'     => false,  //不在url链接中的project-team后加s 复数
                 'extraPatterns' => [
                     'GET index'       => 'index',//获取最新的版本号
                 ],
                ],

                //新闻相关接口
                ['class'         => 'yii\rest\UrlRule',
                 'controller'    => 'v2/news-manage',
                 'pluralize'     => false,  //不在url链接中的project-team后加s 复数
                 'extraPatterns' => [
                     'GET banner-list'       => 'banner-list',//banner展示
                     'GET article-list'      => 'article-list',//新闻列表展示
                     'GET article-detail'    => 'article-detail',//新闻详情展示
                     'GET get-video-details' => 'get-video-details',//视频详情展示
                     'GET get-audio-details' => 'get-audio-details',//音频详情展示
                     'GET specia-list'       => 'specia-list',//获取专题列表
                     'GET related'           => 'related',//获取相关推荐
                     'GET agreement'         => 'agreement',//获取协议
                 ],
                ],
                //获取图集列表
                ['class'         => 'yii\rest\UrlRule',
                 'controller'    => 'v2/atlas-manage',
                 'pluralize'     => false,  //不在url链接中的project-team后加s 复数
                 'extraPatterns' => [
                     'GET get-atlas-list' => 'get-atlas-list',//获取图集列表
                 ],
                ],
                //获取栏目列表
                ['class'         => 'yii\rest\UrlRule',
                 'controller'    => 'v2/category-manage',
                 'pluralize'     => false,  //不在url链接中的project-team后加s 复数
                 'extraPatterns' => [
                     'GET get-category-list' => 'get-category-list',//获取栏目列表
                 ],
                ],
                //获取频道列表
                ['class'         => 'yii\rest\UrlRule',
                 'controller'    => 'v2/channel-manage',
                 'pluralize'     => false,  //不在url链接中的project-team后加s 复数
                 'extraPatterns' => [
                     'GET get-channel-list' => 'get-channel-list',//获取频道列表
                 ],
                ],
                //搜索
                ['class'         => 'yii\rest\UrlRule',
                 'controller'    => 'v2/search-manage',
                 'pluralize'     => false,  //不在url链接中的project-team后加s 复数
                 'extraPatterns' => [
                     'GET search' => 'search',//获取搜索列表
                 ],
                ],
                //APP底部视频和APP底部电视和电台
                ['class'         => 'yii\rest\UrlRule',
                 'controller'    => 'v2/special-manage',
                 'pluralize'     => false,  //不在url链接中的project-team后加s 复数
                 'extraPatterns' => [
                     'GET get-affiliated' => 'get-affiliated',//获取视频列表
                     'GET get-tv-radio' => 'get-tv-radio',//获取电视和电台列表
                 ],
                ],
                //用户相关接口
                ['class' => 'yii\rest\UrlRule',
                 'controller' => 'v2/user',
                 'pluralize'     => false,  //不在url链接中的project-team后加s 复数
                 'extraPatterns'=>[
                     'POST login'=>'login',//登录
                     'POST signup'=>'signup',//注册
                     'POST send-sms'=>'send-sms',//手机验证码
                     'POST update-password'=>'update-password',//修改密码
                     'POST forgot-password'=>'forgot-password',//忘记密码
                     'POST confirm-password'=>'confirm-password',//忘记密码确认重置
                     'POST feedback'=>'feedback',//用户反馈接口
                     'POST nickname-update'=>'nickname-update',//修改用户昵称接口
                     'POST mobile-replace'=>'mobile-replace',//绑定手机号接口与更换手机号
                     'GET get-member'=>'get-member',//获取用户信息
                     'POST mobile-unlock'=>'mobile-unlock',//解绑手机号
                 ],
                ],
                //视频定位
                ['class' => 'yii\rest\UrlRule',
                 'controller' => 'v2/video-manage',
                 'pluralize'     => false,  //不在url链接中的project-team后加s 复数
                 'extraPatterns'=>[
                     'POST positioning'=>'positioning',//记录视频时间定位
                     'GET get-positioning'=>'get-positioning',//获取视频时间定位
                 ],
                ],

                //新闻相关接口
                ['class'         => 'yii\rest\UrlRule',
                 'controller'    => 'v1/news-manage',
                 'pluralize'     => false,  //不在url链接中的project-team后加s 复数
                 'extraPatterns' => [
                     'GET banner-list'       => 'banner-list',//banner展示
                     'GET article-list'      => 'article-list',//新闻列表展示
                     'GET article-detail'    => 'article-detail',//新闻详情展示
                     'GET get-video-details' => 'get-video-details',//视频详情展示
                     'GET get-audio-details' => 'get-audio-details',//音频详情展示
                     'GET specia-list'       => 'specia-list',//获取专题列表
                     'GET related'           => 'related',//获取相关推荐
                     'GET agreement'         => 'agreement',//获取协议
                 ],
                ],
                //获取图集列表
                ['class'         => 'yii\rest\UrlRule',
                 'controller'    => 'v1/atlas-manage',
                 'pluralize'     => false,  //不在url链接中的project-team后加s 复数
                 'extraPatterns' => [
                     'GET get-atlas-list' => 'get-atlas-list',//获取图集列表
                 ],
                ],
                //获取栏目列表
                ['class'         => 'yii\rest\UrlRule',
                 'controller'    => 'v1/category-manage',
                 'pluralize'     => false,  //不在url链接中的project-team后加s 复数
                 'extraPatterns' => [
                     'GET get-category-list' => 'get-category-list',//获取栏目列表
                 ],
                ],
                //获取频道列表
                ['class'         => 'yii\rest\UrlRule',
                 'controller'    => 'v1/channel-manage',
                 'pluralize'     => false,  //不在url链接中的project-team后加s 复数
                 'extraPatterns' => [
                     'GET get-channel-list' => 'get-channel-list',//获取频道列表
                 ],
                ],
                //搜索
                ['class'         => 'yii\rest\UrlRule',
                 'controller'    => 'v1/search-manage',
                 'pluralize'     => false,  //不在url链接中的project-team后加s 复数
                 'extraPatterns' => [
                     'GET search' => 'search',//获取搜索列表
                 ],
                ],
                //APP底部视频和APP底部电视和电台
                ['class'         => 'yii\rest\UrlRule',
                 'controller'    => 'v1/special-manage',
                 'pluralize'     => false,  //不在url链接中的project-team后加s 复数
                 'extraPatterns' => [
                     'GET get-affiliated' => 'get-affiliated',//获取视频列表
                     'GET get-tv-radio' => 'get-tv-radio',//获取电视和电台列表
                 ],
                ],
                //用户相关接口
                ['class' => 'yii\rest\UrlRule',
                 'controller' => 'v1/user',
                 'pluralize'     => false,  //不在url链接中的project-team后加s 复数
                 'extraPatterns'=>[
                     'POST login'=>'login',//登录
                     'POST signup'=>'signup',//注册
                     'POST send-sms'=>'send-sms',//手机验证码
                     'POST update-password'=>'update-password',//修改密码
                     'POST forgot-password'=>'forgot-password',//忘记密码
                     'POST confirm-password'=>'confirm-password',//忘记密码确认重置
                     'POST feedback'=>'feedback',//用户反馈接口
                     'POST nickname-update'=>'nickname-update',//修改用户昵称接口
                     'POST mobile-replace'=>'mobile-replace',//绑定手机号接口与更换手机号
                     'GET get-member'=>'get-member',//获取用户信息
                     'POST mobile-unlock'=>'mobile-unlock',//解绑手机号
                 ],
                ],
                //视频定位
                ['class' => 'yii\rest\UrlRule',
                 'controller' => 'v1/video-manage',
                 'pluralize'     => false,  //不在url链接中的project-team后加s 复数
                 'extraPatterns'=>[
                     'POST positioning'=>'positioning',//记录视频时间定位
                     'GET get-positioning'=>'get-positioning',//获取视频时间定位
                 ],
                ],

            ],
        ],

    ],
    'params' => $params,
];
