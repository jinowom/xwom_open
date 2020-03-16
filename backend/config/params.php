<?php
return [
    'adminEmail' => 'admin@example.com',
    'name' => 'xwom系统',//设置默认系统站点信息常量 引用方法：Yii::$app->params['name']；
    'pageSize' => 10, //默认显示条数
    'limitsJson' => \yii\helpers\Json::encode([1,2,3,10, 20, 50, 100, 500, 1000]), //默认列表显示
    /** ------ 开发者信息 ------ **/
    'exploitDeveloper' => 'Developer',
    'exploitFullName' => 'xwom应用开发引擎',
    'exploitOfficialWebsite' => '<a href="https://www.jinostart.com" target="_blank">https://www.jinostart.com</a>',
    'exploitGitHub' => '<a href="https://www.jinostart.com" target="_blank">https://www.jinostart.com</a>',
    'exploitDate' => '2020-02-07',
    /** ------ 总管理员配置 ------ **/
    
    /** ------ 日志记录 ------ **/
    //'user.log' => true,
    //'user.log.level' => ['warning', 'error'], // 级别 ['success', 'info', 'warning', 'error']
    //'user.log.except.code' => [404], // 不记录的code
];
