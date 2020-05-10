<?php
namespace moxuandi\helpers\oauth;

use yii\authclient\OAuth2;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * QQ 互联的第三方登录
 *
 * @author zhangmoxuan <1104984259@qq.com>
 * @link http://www.zhangmoxuan.com
 * @QQ 1104984259
 * @Date 2019-8-4
 *
 * @see https://github.com/yiisoft/yii2-authclient/tree/master/docs/guide-zh-CN 用于 Yii 2 的 AuthClient 扩展
 * @see https://connect.qq.com/manage.html#/ 申请地址
 * @see http://wiki.connect.qq.com/get_user_info 接口说明
 * @see http://wiki.open.qq.com/wiki/%E3%80%90QQ%E7%99%BB%E5%BD%95%E3%80%91OAuth2.0%E5%BC%80%E5%8F%91%E6%96%87%E6%A1%A3 【QQ登录】OAuth2.0开发文档
 */
class QQClient extends OAuth2
{
    /**
     * @var string
     */
    public $authUrl = 'https://graph.qq.com/oauth2.0/authorize';
    /**
     * @var string
     */
    public $tokenUrl = 'https://graph.qq.com/oauth2.0/token';
    /**
     * @var string
     */
    public $apiBaseUrl = 'https://graph.qq.com';


    /**
     * @return array
     */
    protected function initUserAttributes()
    {
        $user = $this->getUser();
        $response = $this->api('user/get_user_info', 'GET', [
            'oauth_consumer_key' => $user['client_id'],
            'openid' => $user['openid'],
        ]);  // 响应数据见本文件尾部

        return ArrayHelper::merge([
            'client' => 'qq',
            'openid' => $user['openid'],
        ], $response);
    }

    /**
     * 因为`oauth2.0/me`返回字符串`callback( {\"client_id\":\"101411034\",\"openid\":\"B30F810381409AB16AE39F5878D6CD2C\"} );`, 所以使用`file_get_contents()`方法.
     *
     * @return bool|mixed
     */
    protected function getUser()
    {
        $response = file_get_contents('https://graph.qq.com/oauth2.0/me?access_token=' . $this->accessToken->token);
        if(strpos($response, 'callback') !== false){
            $lpos = strpos($response, '(');
            $rpos = strrpos($response, ')');
            $result = substr($response, $lpos + 1, $rpos - $lpos -1);  // 截取得到` {\"client_id\":\"101411034\",\"openid\":\"B30F810381409AB16AE39F5878D6CD2C\"} `
            return Json::decode($result);
        }else{
            return false;
        }
    }

    /**
     * 该方法主要影响`a`标签和`span`标签的`class`属性.
     * 仅在调用`AuthChoice::widget()`方法时生效.
     * @return string
     */
    protected function defaultName()
    {
        return 'QQ';
    }

    /**
     * 该方法主要影响`a`标签的`title`属性.
     * 仅在调用`AuthChoice::widget()`方法时生效.
     * @return string
     */
    protected function defaultTitle()
    {
        return 'QQ 登录';
    }

    /**
     * 该方法主要影响弹出窗口的大小.
     * 仅在调用`AuthChoice::widget()`方法且`$popupMode = true`时生效.
     * @return array
     */
    protected function defaultViewOptions()
    {
        return [
            'popupWidth' => 860,
            'popupHeight' => 480,
        ];
    }


    /**
     * `get_user_info`接口返回的是json, `$this->api()`方法返回的是数组:
     * @see http://wiki.connect.qq.com/get_user_info
     * [
     *     'ret' => 0
     *     'msg' => ''
     *     'is_lost' => 0
     *     'nickname' => '张墨轩'
     *     'gender' => '男'
     *     'province' => '河南'
     *     'city' => '郑州'
     *     'year' => '1993'
     *     'constellation' => ''
     *     'figureurl' => 'http://qzapp.qlogo.cn/qzapp/101411034/B30F810381409AB16AE39F5878D6CD2C/30'
     *     'figureurl_1' => 'http://qzapp.qlogo.cn/qzapp/101411034/B30F810381409AB16AE39F5878D6CD2C/50'
     *     'figureurl_2' => 'http://qzapp.qlogo.cn/qzapp/101411034/B30F810381409AB16AE39F5878D6CD2C/100'
     *     'figureurl_qq_1' => 'http://thirdqq.qlogo.cn/qqapp/101411034/B30F810381409AB16AE39F5878D6CD2C/40'
     *     'figureurl_qq_2' => 'http://thirdqq.qlogo.cn/qqapp/101411034/B30F810381409AB16AE39F5878D6CD2C/100'
     *     'is_yellow_vip' => '0'
     *     'vip' => '0'
     *     'yellow_vip_level' => '0'
     *     'level' => '0'
     *     'is_yellow_year_vip' => '0'
     * ]
     */
}
