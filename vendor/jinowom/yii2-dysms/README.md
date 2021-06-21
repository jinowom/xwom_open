# yii2-aliyun-dysms

## 安装

```bash
composer require "jinowom/yii2-dysms:dev-master"
```

> or添加下列代码在composer.json文件中并执行composer update 操作

```bash
{
    "require": {
       "jinowom/yii2-dysms":"dev-master"
    }
}
```

## 设置方法

#### 全局使用
> 在config/main.php配置文件中定义component配置信息
```bash
'components' => [
  .....
  'aliyun' => [
      'class' => 'jinowom\aliyun\Sms',
      'accessKeyId' => '123455',
      'accessKeySecret' => '122345666'
    ],
  ....
]

```
> 代码中调用（调用短信发送接口示例）

```bash
// 单条发送
$response = Yii::$app->aliyun->sendSms(
            "孙坤峰", // 短信签名
            "SMS_76350132", // 短信模板编号
            "136*****134", // 短信接收者
            Array(  // 短信模板中字段的值
                "code"=>"12345",
                "product"=>"dsd"
            ),
            "123"
        );
        print_r($response);
        
//批量发送

$response = Yii::$app->aliyun->sendBatchSms(
            array("孙坤峰","孙坤峰"), // 短信签名
            "SMS_76350132", // 短信模板编号
            array("136*****134","180*****459"), // 短信接收者
            array(array(  // 短信模板中字段的值
                "code"=>"12345",
                "product"=>"dsd"
            ),array(  // 短信模板中字段的值
                "code"=>"123456",
                "product"=>"dsd"
            )),
            "123"
        );
//批量发送 签名、手机号、模板字段 数组长度必须相等

如上所示 180*******259收到的短信 签名是"孙坤峰" 内容为  123456
```


