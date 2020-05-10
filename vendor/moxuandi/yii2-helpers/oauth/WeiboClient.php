<?php
namespace moxuandi\helpers\oauth;

use yii\authclient\OAuth2;
use yii\helpers\VarDumper;

/**
 * 微博第三方登录
 *
 * @author zhangmoxuan <1104984259@qq.com>
 * @link http://www.zhangmoxuan.com
 * @QQ 1104984259
 * @Date 2019-8-4
 *
 * @see https://github.com/yiisoft/yii2-authclient/tree/master/docs/guide-zh-CN 用于 Yii 2 的 AuthClient 扩展
 */
class WeiboClient extends OAuth2
{
    /**
     * @var string
     */
    public $authUrl = 'https://api.weibo.com/oauth2/authorize';
    /**
     * @var string
     */
    public $tokenUrl = 'https://api.weibo.com/oauth2/access_token';
    /**
     * @var string
     */
    public $apiBaseUrl = 'https://api.weibo.com/2';


    /**
     * @return array|void
     */
    protected function initUserAttributes()
    {
        /*$user = $this->api('users/show.json', 'GET', ['uid' => $this->user->uid]);
        return [
            'client' => 'weibo',
            'openid' => $user['id'],
            'nickname' => $user['name'],
            'gender' => $user['gender'],
            'location' => $user['location'],
        ];*/

        $user = $this->getUser();
        $response = $this->api('users/show.json', 'GET', ['uid' => $user->uid]);

        VarDumper::dump($response, 10, true);die;
    }

    protected function getUser()
    {
        //$str = file_get_contents('https://api.weibo.com/2/account/get_uid.json?access_token=' . $this->accessToken->token);
        //return json_decode($str);

        $response = $this->api('account/get_uid.json');
        VarDumper::dump($response, 10, true);die;
    }

    /**
     * 该方法主要影响`a`标签和`span`标签的`class`属性.
     * 仅在调用`AuthChoice::widget()`方法时生效.
     * @return string
     */
    protected function defaultName()
    {
        return 'Weibo';
    }

    /**
     * 该方法主要影响`a`标签的`title`属性.
     * 仅在调用`AuthChoice::widget()`方法时生效.
     * @return string
     */
    protected function defaultTitle()
    {
        return '新浪微博登录';
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
}
