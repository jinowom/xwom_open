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
    'channelRole' => 'xportal/channel-manage/views',//频道权限最大目录
    'categoryRole' => 'xportal/category-manage/views',//栏目权限最大目录
    'specialRole' => 'xportal/xportal-special/views',//专题权限最大目录
    /** ------ 总管理员配置 ------ **/
    
    /** ------ 日志记录 ------ **/
    //'user.log' => true,
    //'user.log.level' => ['warning', 'error'], // 级别 ['success', 'info', 'warning', 'error']
    //'user.log.except.code' => [404], // 不记录的code
    //RSA公钥私钥配置
 'private_key'=> "-----BEGIN RSA PRIVATE KEY-----
    MIICWwIBAAKBgQCCHbwZCOhcm/pIyKXC1DO/Vdd56jOFBvZXzQaxmBk25OGPMJ2+
    bVS+/QWQ3oDZ6v1MUVTmI8gUXTcvdqdq7bJNiuX7qhL9UKFDKlEu8Um41BYXq8Eu
    4GByw91jHbLoVlTC8+6uZgFIuBY3ibPFgld0dBNle3IS4yQLBPeILYPqHQIDAQAB
    AoGALTwpKIrwPUH8wVEAT7t2Qg6V2syRHK5O9jdRHGzEV1E7GYzNSma4D63nQXYZ
    ValcZivgWCIYbPv7M4UMrx/z3IFjpKX6A3Eecc8aycDRF/uLdvEjHrRSax42m9hE
    MG07bbnBewsouZcIndONWfqwOcqM/C4WmkVMTuH4Zkwl/AECQQDQoev0r2IvaGUm
    c7YhhYkBhiQeSKz1sEcpgxmnvkO12JwAmi+SMLVEaGIjinBMXHnzKU4Yg+rKplxT
    AQKk4dLHAkEAn6hO4aD7uUpzy6I58IsZY2wl7YmWgd3W6/OFsrgkElRMdx1AH6M4
    IPUmV0tWjwjFWUmn+ysvsTncMpmqKCK3+wJAbPPyllC09M8O69rHxY/H8bzMxefs
    M05Ai4REdJ5fG+sn5QSgTTcUosnkXm0gojA1G3B5sUHK7tOcKVjAubyY5wJAYWld
    h4ijTXBBqnL2iu8ztFed5IpYDDCAG0JfxqVXTN+mL97m6ua5LlKk7AoJbAfb8Rhh
    p3u4A5fb4/uhuA9G1QJACvObj4yvGIYz2DAU11R00NKtuQzKnwDhhtwJL9BIsJ1g
    Baz+rO56rjxHu6pJEQaNHCcjCvXoFxSlZpeRuE6UfA==
-----END RSA PRIVATE KEY-----",//私钥 这一行不能移动 只可以这样放 动了秘钥就会不正确

'public_key' => "-----BEGIN PUBLIC KEY-----
    MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCCHbwZCOhcm/pIyKXC1DO/Vdd5
    6jOFBvZXzQaxmBk25OGPMJ2+bVS+/QWQ3oDZ6v1MUVTmI8gUXTcvdqdq7bJNiuX7
    qhL9UKFDKlEu8Um41BYXq8Eu4GByw91jHbLoVlTC8+6uZgFIuBY3ibPFgld0dBNl
    e3IS4yQLBPeILYPqHQIDAQAB
-----END PUBLIC KEY-----",//公钥
];
