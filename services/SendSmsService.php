<?php

namespace services;

use Yii;
use common\components\Service;
use Qcloud\Sms\SmsSingleSender;
use common\models\VerifyCodeHistory;
use common\models\config\ConfigSms;
use jinowom\aliyun\Sms;
use services\ErrorService;
use services\ResponseService;

/**
 * Class SendsmsService
 * @package services
 * author:rjl
 */
class SendSmsService extends Service
{
    /**
     * 通知类短信
     * @return int
     * @throws \yii\db\Exception
     */
    public static function getInformSms($mobile = '',$variable= [])
    {
        if (empty($mobile) || empty($variable)) {
            return ResponseService::response(ErrorService::PARAMETER_ERROR);
        }
        
        $getSms = ConfigSms::find()->from(ConfigSms::tableName())->select(['id','sdk_com', 'sdk_type', 'access_key_id', 'access_key_secret', 'access_key_sign', 'model_id', 'sendvariable'])->Where(['is_del' => 0, 'status' => 1, 'send_type' => 2])->orderBy('id asc')->asArray()->all();
        
        if (empty($getSms)) {
            return ResponseService::response(ErrorService::CONTENT_EMPTY);
        }

        foreach ($getSms as $v) {
            if ($v['sdk_type'] == 1) {
                //阿里
                $result = Yii::$app->aliyun->sendSms(
                    $v['access_key_sign'], //短信签名
                    $v['model_id'], //短信模板编号
                    $mobile, //短信接收者
                    $variable
                );
                $response = json_decode($result, true);
                if (($response['code'] == 200)) {
                    break;
                }
            } 
            //腾讯通知类还没有
            /* elseif ($v['sdk_type'] == 2) {
                //腾讯
                $ssender = new SmsSingleSender($v['access_key_id'], $v['access_key_secret']);
                $result = $ssender->sendWithParam("86", $mobile, $v['model_id'], [], $v['access_key_sign'], "", "");
                $response = json_decode($result, true);
                if (($response['result'] == 0) && ($response['errmsg'] == 'OK')) {
                    break;
                }
            } */
        }
    }

    /**
     * 验证码类短信
     */
    public static function getCodeSms($mobile='',$type='')
    {
        if (empty($mobile) || empty($type)) {
            return ResponseService::response(ErrorService::PARAMETER_ERROR);
        }
        $verifyModel = new VerifyCodeHistory();

        //验证码是否过期
        $timeout = $verifyModel->timeoutIsSms($mobile, $type);
        if (!$timeout) {
            return ResponseService::response(ErrorService::APP_CODE_TIME_ERROR);
        }

        $getSms = ConfigSms::find()->from(ConfigSms::tableName())->select(['id', 'sdk_com', 'sdk_type', 'access_key_id', 'access_key_secret', 'access_key_sign', 'model_id', 'sendvariable'])->Where(['is_del' => 0, 'status' => 1, 'send_type' => 1])->orderBy('id desc')->asArray()->all();

        if (empty($getSms)) {
            return ResponseService::response(ErrorService::CONTENT_EMPTY);
        }
        $code = $verifyModel->RandCode();//获取验证码
        foreach ($getSms as $v) {
            if ($v['sdk_type'] == 1) {
                $variable = [
                    "code" => $code
                ];
                //阿里
                $result = Yii::$app->aliyun->sendSms(
                    $v['access_key_sign'], //短信签名
                    $v['model_id'], //短信模板编号
                    $mobile, //短信接收者
                    $variable, //短信模板中字段的值
                    "123"
                );
                $response = json_decode($result, true);

                if ($response['code'] == 200) {
                    //插入数据库
                    $insertData = [
                        'mobile' => $mobile,
                        'code' => $code,
                        'type' => $type,
                    ];
    
                    $verifyModel->setAttributes($insertData);
                    $inserRes = $verifyModel->save();
                    if ($inserRes) {
                        return ResponseService::response(ErrorService::STATUS_SUCCESS);
                    }
                    return ResponseService::response(ErrorService::SMS_SEND_FAILED);
                } elseif ($response['code'] == 108) {
                    return ResponseService::response(ErrorService::ACHIEVE_CEILING);
                }
            } elseif ($v['sdk_type'] == 2) {
                $variable = [$code, 5];
                //腾讯
                $ssender = new SmsSingleSender($v['access_key_id'], $v['access_key_secret']);
                $result = $ssender->sendWithParam("86", $mobile, $v['model_id'], $variable, $v['access_key_sign'], "", "");
                $response = json_decode($result, true);

                if ($response['result'] == 0 && ($response['errmsg'] == 'OK')) {
                    //插入数据库
                    $insertData = [
                        'mobile' => $mobile,
                        'code' => $code,
                        'type' => $type,
                    ];

                    $verifyModel->setAttributes($insertData);
                    $inserRes = $verifyModel->save();
                    if ($inserRes) {
                        return ResponseService::response(ErrorService::STATUS_SUCCESS);
                    }
                    return ResponseService::response(ErrorService::SMS_SEND_FAILED);
                }
            }
            break; //终止循环
        }
    }

}