<?php
return [
    // 短信配置
    'SMS' => [
        'APPID' => 1400049147,
        'APPKEY' => 'b1e8af6356bad2353bf464eb0113236b',
        'SMSSIGN' => 'xpaper报刊',
        "TEMP_ID" => '402252', //模板ID 根据腾讯云申请更换
        "MODIFY_CODE" => 4  //修改手机号码
    ],
    
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
];
