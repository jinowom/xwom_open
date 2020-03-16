<?php
Yii::setAlias('@common', dirname(__DIR__));//配置层路径设置
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');//前台路径设置
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');//后台业务路径设置
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');//控制台路径设置
Yii::setAlias('@services', dirname(dirname(__DIR__)) . '/services');//服务层路径设置
//接下来下面这些都要用到
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');//api接口
Yii::setAlias('@html5', dirname(dirname(__DIR__)) . '/html5');//html5
Yii::setAlias('@oauth2', dirname(dirname(__DIR__)) . '/oauth2');//第三方授权
Yii::setAlias('@addons', dirname(dirname(__DIR__)) . '/addons');//应用管理，稍后开发组件管理模块，可以安装，卸载，编辑
Yii::setAlias('@root', dirname(dirname(__DIR__)) . '/');//根目录设置

Yii::setAlias('@upload', dirname(dirname(__DIR__)) . '/upload');//暂定独立的上传附件目录，后续可能要采取跨域服务器专门存储

// 各自应用域名配置，如果没有配置应用独立域名请忽略
Yii::setAlias('@attachment', dirname(dirname(__DIR__)) . '/backend/web'); // 本地资源目录绝对路径
Yii::setAlias('@attachurl', ''); // 资源目前相对路径，可以带独立域名
Yii::setAlias('@backendUrl', '');
Yii::setAlias('@frontendUrl', '');
Yii::setAlias('@html5Url', '');
Yii::setAlias('@apiUrl', '');
Yii::setAlias('@storageUrl', '');
Yii::setAlias('@oauth2Url', '');
Yii::setAlias('@merchantUrl', '');
