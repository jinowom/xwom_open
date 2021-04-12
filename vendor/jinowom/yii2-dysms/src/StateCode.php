<?php
/**
 * Created by PhpStorm.
 * User: jinowom
 * Date: 17/10/23
 * Time: 下午14:10
 */

namespace jinowom\aliyun;


class StateCode
{
    public static function getMsg(){
        return [
            'isv.ACCOUNT_NOT_EXISTS' => [
                'code' => 100,
                'message' => '账户信息不存在'
            ],
            'isv.ACCOUNT_ABNORMAL' => [
                'code' => 101,
                'message' => '账户信息异常'
            ],
            'isv.SMS_TEMPLATE_ILLEGAL' => [
                'code' => 102,
                'message' => '模板不合法'
            ],
            'isv.SMS_SIGNATURE_ILLEGAL' => [
                'code' => 103,
                'message' => '签名不合法'
            ],
            'isv.MOBILE_NUMBER_ILLEGAL' => [
                'code' => 104,
                'message' => '手机号码格式错误'
            ],
            'isv.MOBILE_COUNT_OVER_LIMIT' => [
                'code' => 105,
                'message' => '手机号码数量超过限制'
            ],
            'isv.TEMPLATE_MISSING_PARAMETERS' => [
                'code' => 106,
                'message' => '短信模板变量缺少参数'
            ],
            'isv.INVALID_PARAMETERS' => [
                'code' => 107,
                'message' => '参数异常'
            ],
            'isv.BUSINESS_LIMIT_CONTROL' => [
                'code' => 108,
                'message' => '验证码发送达到上限'
            ],
            'isv.INVALID_JSON_PARAM' => [
                'code' => 109,
                'message' => 'JSON参数不合法'
            ],
            'isv.BLACK_KEY_CONTROL_LIMIT' => [
                'code' => 110,
                'message' => '模板参数使用不合法文字'
            ],
            'isv.PARAM_NOT_SUPPORT_URL' => [
                'code' => 111,
                'message' => '不支持url为变量'
            ],
            'isv.PARAM_LENGTH_LIMIT' => [
                'code' => 112,
                'message' => '变量长度受限'
            ],
            'isv.AMOUNT_NOT_ENOUGH' => [
                'code' => 113,
                'message' => '余额不足'
            ],
            'isv.appkey-not-exists' => [
                'code' => 114,
                'message' => 'appKey不存在'
            ],
            25 => [
                'code' => 115,
                'message' => 'secretKey不合法'
            ]
        ];
    }
}