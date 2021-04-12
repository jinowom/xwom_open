<?php

namespace services;

/**
 * Class ErrorService
 *
 * @method static ErrorService STATUS_SUCCESS 200:成功
 * @method static ErrorService STATUS_FAILED 201:失败
 * @method static ErrorService PARAMETER_ERROR 203:参数错误
 * @method static ErrorService STATUS_PARAMETER_VALIDATION_ERROR 204:参数验证错误类型
 * @method static ErrorService NO_OPERATION_PERMISSIONS 403:对不起，您现在还没获此操作的权限
 * @method static ErrorService PAGE_ERROR 404:页面未找到
 * 
 * @method static ErrorService CHANNEL_NO_EMPTY 301:频道为空
 * @method static ErrorService CHANNEL_ID_NO_EMPTY 302:频道id为空
 * @method static ErrorService CATEGORY_NO_EMPTY 303:栏目为空
 * @method static ErrorService CATEGORY_ID_NO_EMPTY 304:栏目id为空
 * @method static ErrorService NOT_FOUND_VERSION 305:未找到版本
 * @method static ErrorService TYPE_EMPTY 306:类型为空
 * @method static ErrorService TYPE_ERROR 307:类型错误
 * @method static ErrorService CHANNEL_OR_CATEGORY_ID_EMPTY 308:频道和栏目id为空
 * @method static ErrorService NOT_FOUND_REPOSITORY 309:未找到资源库
 * @method static ErrorService NOT_FOUND_NEWS 310:未找到新闻
 * @method static ErrorService NEWS_ID_NO_EMPTY 311:新闻id为空
 * @method static ErrorService CATEGORY_BIND_EMPTY 312:栏目频道为空
 * @method static ErrorService AFFILIATE_ID_EMPTY 313:附属标识为空
 * @method static ErrorService MOBILE_EMPTY 314:手机号为空
 * @method static ErrorService PASSWORD_EMPTY 315:密码为空
 * @method static ErrorService CODE_EMPTY 316:验证码为空
 * @method static ErrorService MEMBER_EMPTY 317:用户信息为空
 * @method static ErrorService TOKEN_OVERDUE 318:用户登录过期
 * @method static ErrorService CONTENT_EMPTY 319:信息为空
 * @method static ErrorService NICKNAME_EMPTY 320:昵称为空
 * @method static ErrorService USER_NAME_EMPTY 321:用户名为空
 * @method static ErrorService TOKEN_EMPTY 322:token为空
 * @method static ErrorService MOBILE_REPEAT 323:手机号重复
 * @method static ErrorService ACCOUNT_PASSWORD_ERROR 324:密码或者账号不正确
 * @method static ErrorService IP_ERROR 325:IP为空
 * @method static ErrorService IP_DISABLE 326:IP已被禁用
 * @method static ErrorService SMS_SEND 327:已经发送新手机号码,请注意查收
 * @method static ErrorService SMS_SEND_FAILED 328:短信发送失败
 * @method static ErrorService EXISTING_OK 329:已存在
 * @method static ErrorService PASSWORD_COMBINATION_ERROR 330:必须是数字和字母组合
 * @method static ErrorService PASSWORD_LENGTH_ERROR 331:密码必须为6-30位
 * @method static ErrorService BINDING_NO_ERROR 332:该用户未绑定手机号，请绑定手机号
 * @method static ErrorService ACHIEVE_CEILING 333:验证码发送达到上限
 * @method static ErrorService APP_CODE_TIME_ERROR 334:请勿频繁请求
 * @method static ErrorService MEMBER_NAME_ERROR 335:该用户名已经被占用
 * @method static ErrorService FILE_EMPTY 336:文件地址为空
 * @method static ErrorService TIME_EMPTY 337:时间为空
 * @method static ErrorService MOBILE_INCONFORMITY 338:手机号不一致
 *
 * @package App\Services
 */
class ErrorService
{
    const STATUS_SUCCESS = 200; //成功
    const STATUS_FAILED = 201; //失败
    const PARAMETER_ERROR = 203; //参数错误
    const STATUS_PARAMETER_VALIDATION_ERROR = 204; //参数验证错误类型
    const NO_OPERATION_PERMISSIONS = 403; //对不起，您现在还没获此操作的权限
    const PAGE_ERROR = 404; //页面未找到

    //API接口信息码
    const CHANNEL_NO_EMPTY = 301; //频道为空
    const CHANNEL_ID_NO_EMPTY = 302; //频道id为空
    const CATEGORY_NO_EMPTY = 303; //栏目为空
    const CATEGORY_ID_NO_EMPTY = 304; //栏目id为空
    const NOT_FOUND_VERSION = 305; //未找到版本
    const TYPE_EMPTY = 306; //类型为空
    const TYPE_ERROR = 307; //类型错误
    const CHANNEL_OR_CATEGORY_ID_EMPTY = 308; //频道和栏目id为空
    const NOT_FOUND_REPOSITORY = 309; //未找到资源库
    const NOT_FOUND_NEWS = 310; //未找到新闻
    const NEWS_ID_NO_EMPTY = 311; //新闻id为空
    const CATEGORY_BIND_EMPTY = 312; //栏目频道为空
    const AFFILIATE_ID_EMPTY = 313; //附属标识为空
    const MOBILE_EMPTY = 314; //手机号为空
    const PASSWORD_EMPTY = 315; //密码为空
    const CODE_EMPTY = 316; //验证码为空
    const MEMBER_EMPTY = 317; //用户信息为空
    const TOKEN_OVERDUE = 318; //用户登录过期
    const CONTENT_EMPTY = 319; //信息为空
    const NICKNAME_EMPTY = 320; //昵称为空
    const USER_NAME_EMPTY = 321; //用户名为空
    const TOKEN_EMPTY = 322; //token为空
    const MOBILE_REPEAT = 323; //手机号重复
    const ACCOUNT_PASSWORD_ERROR = 324; //密码或者账号不正确
    const IP_ERROR = 325; //IP为空
    const IP_DISABLE = 326; //IP已被禁用
    const SMS_SEND = 327; //已经发送新手机号码,请注意查收
    const SMS_SEND_FAILED = 328; //短信发送失败
    const EXISTING_OK = 329; //已存在
    const PASSWORD_COMBINATION_ERROR = 330; //必须是数字和字母组合
    const PASSWORD_LENGTH_ERROR = 331; //密码必须为6-30位
    const BINDING_NO_ERROR = 332; //该用户未绑定手机号，请绑定手机号
    const ACHIEVE_CEILING = 333; //验证码发送达到上限
    const APP_CODE_TIME_ERROR = 334; //请勿频繁请求
    const MEMBER_NAME_ERROR = 335;//该用户名已经被占用
    const FILE_EMPTY = 336;//文件地址为空
    const TIME_EMPTY = 337;//时间为空
    const MOBILE_INCONFORMITY = 338;//手机号不一致

    /**
     * 错误码提示信息
     *
     * @param $code
     *
     * @return string
     */
    public static function getMessage($code)
    {
        $map = [
            self::STATUS_SUCCESS                    => '成功',
            self::STATUS_FAILED                     => '失败',
            self::PARAMETER_ERROR                   => '参数错误',
            self::STATUS_PARAMETER_VALIDATION_ERROR => '参数验证错误类型',
            self::NO_OPERATION_PERMISSIONS          => '对不起，您现在还没获此操作的权限',
            self::PAGE_ERROR                        => '页面未找到',

            //API接口信息码
            self::CHANNEL_NO_EMPTY             => '频道为空',
            self::CHANNEL_ID_NO_EMPTY          => '频道id为空',
            self::CATEGORY_NO_EMPTY            => '栏目为空',
            self::CATEGORY_ID_NO_EMPTY         => '栏目id为空',
            self::NOT_FOUND_VERSION            => '未找到版本',
            self::TYPE_EMPTY                   => '类型为空',
            self::TYPE_ERROR                   => '类型错误',
            self::CHANNEL_OR_CATEGORY_ID_EMPTY => '频道和栏目id为空',
            self::NOT_FOUND_REPOSITORY         => '未找到资源库',
            self::NOT_FOUND_NEWS               => '未找到新闻',
            self::NEWS_ID_NO_EMPTY             => '新闻id为空',
            self::CATEGORY_BIND_EMPTY          => '栏目频道为空',
            self::AFFILIATE_ID_EMPTY           => '附属标识为空',
            self::MOBILE_EMPTY                 => '手机号为空',
            self::PASSWORD_EMPTY               => '密码为空',
            self::CODE_EMPTY                   => '验证码为空',
            self::MEMBER_EMPTY                 => '用户信息为空',
            self::TOKEN_OVERDUE                => '用户登录过期',
            self::CONTENT_EMPTY                => '信息为空',
            self::NICKNAME_EMPTY               => '昵称为空',
            self::USER_NAME_EMPTY              => '用户名为空',
            self::TOKEN_EMPTY                  => 'token为空',
            self::MEMBER_NAME_ERROR            => '该用户名已经被占用',
            self::MOBILE_REPEAT                => '手机号重复',
            self::ACCOUNT_PASSWORD_ERROR       => '密码或者账号不正确',
            self::IP_ERROR                     => 'IP为空',
            self::IP_DISABLE                   => 'IP已被禁用',
            self::SMS_SEND                     => '已经发送新手机号码,请注意查收',
            self::SMS_SEND_FAILED              => '短信发送失败',
            self::EXISTING_OK                  => '已存在',
            self::PASSWORD_COMBINATION_ERROR   => '必须是数字和字母组合',
            self::PASSWORD_LENGTH_ERROR        => '密码必须为6-30位',
            self::BINDING_NO_ERROR             => '该用户未绑定手机号，请绑定手机号',
            self::ACHIEVE_CEILING              => '验证码发送达到上限',
            self::APP_CODE_TIME_ERROR          => '请勿频繁请求',
            self::FILE_EMPTY                   => '文件地址为空',
            self::TIME_EMPTY                   => '时间为空',
            self::MOBILE_INCONFORMITY          => '手机号不一致',
        ];

        return in_array((string)$code, array_keys($map)) ? $map[(string)$code] : '未知的错误类型';
    }
}
