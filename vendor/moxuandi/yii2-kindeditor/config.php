<?php
/**
 * 前后端通信相关的配置
 * see [参考](http://fex.baidu.com/ueditor/#server-config)
 */
return [
    //'rootPath' => dirname(Yii::$app->request->scriptFile),  // 入口文件目录
    //'rootUrl' => Yii::$app->request->hostInfo,  // 访问上传文件的url, 指向'rootPath'

    /* 上传图片{image}配置项 */
    'imageAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],  // 允许上传的文件类型
    'imagePathFormat' => '/uploads/image/{yyyy}{mm}{dd}/{hh}{ii}{ss}_{rand:6}',  // 文件保存路径
    'imageRootPath' => '/uploads/image/',  // 浏览服务器时的根目录


    /* 上传{flash}配置项 */
    'flashAllowFiles' => ['.flv', '.swf'],  // 允许上传的文件类型
    'flashPathFormat' => '/uploads/flash/{yyyy}{mm}{dd}/{hh}{ii}{ss}_{rand:6}',  // 文件保存路径
    'flashRootPath' => '/uploads/flash/',  // 浏览服务器时的根目录


    /* 上传音频{media}配置项 */
    'mediaAllowFiles' => [  // 允许上传的文件类型
        '.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg',
        '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid'
    ],
    'mediaPathFormat' => '/uploads/media/{yyyy}{mm}{dd}/{hh}{ii}{ss}_{rand:6}',  // 文件保存路径
    'mediaRootPath' => '/uploads/media/',  // 浏览服务器时的根目录


    /* 上传文件{file}配置项 */
    'fileAllowFiles' => [  // 允许上传的文件类型
        '.png', '.jpg', '.jpeg', '.gif', '.bmp',
        '.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg',
        '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid',
        '.rar', '.zip', '.tar', '.gz', '.7z', '.bz2', '.cab', '.iso',
        '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx', '.pdf', '.txt', '.md', '.xml'
    ],
    'filePathFormat' => '/uploads/file/{yyyy}{mm}{dd}/{hh}{ii}{ss}_{rand:6}',  // 文件保存路径
    'fileRootPath' => '/uploads/file/',  // 浏览服务器时的根目录
];
