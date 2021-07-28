<?php
return [
    // 短信配置
    'SMS' => [
        'APPID' => '',
        'APPKEY' => '',
        'SMSSIGN' => '',
        "TEMP_ID" => '402252', //模板ID  402252 根据腾讯云申请更换
        "MODIFY_CODE" => 4  //修改手机号码
    ],
    
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
];
