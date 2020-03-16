<?php
/**
 * Created by PhpStorm.
 * User: LIUYANCHENG
 * Date: 2019/10/12
 * Time: 14:05
 */

namespace backend\controllers;


class DouyinController extends BaseController
{
    protected $except = ['index','login'];
    protected $ClientKey = 'awyg5urq2p0jw73a';
    protected $ClientSecret = 'ee3e8bff1d9bcda7c35d6c619ac8bdb7';
    /**
     * @Function
     * @Author 1042463605@qq.com
     * @return string
     */
    public function actionIndex(){
        return $this->render('index');
        //$callBackUrl = 'https://www.mlwch.com/custom/douYinCallback.html';
        //$url         = 'https://open.douyin.com/platform/oauth/connect/?client_key=' . $this->ClientKey . '&response_type=code&scope=user_info&redirect_uri=' . $callBackUrl . '&state=spacing';
       // header('Location:' . $url);
       // exit;
    }

    /**
     * @Function
     * @Author 1042463605@qq.com
     * @return string
     */
    public function actionList(){
        return $this->render('list');
        //$callBackUrl = 'https://www.mlwch.com/custom/douYinCallback.html';
        //$url         = 'https://open.douyin.com/platform/oauth/connect/?client_key=' . $this->ClientKey . '&response_type=code&scope=user_info&redirect_uri=' . $callBackUrl . '&state=spacing';
        // header('Location:' . $url);
        // exit;
    }
    public function actionLogin(){
        return $this->render('login');
    }

    //抖音回调地址
    public function actionDouYinCallback()
    {
        //$res = file_get_contents('php://input');
        $code = Yii::$app->request->get('code');
        if($code){
        $url          = 'https://open.douyin.com/oauth/access_token/?client_key=' . $this->ClientKey . '&client_secret=' . $this->ClientSecret . '&code=' . $code . '&grant_type=authorization_code';
        $res          = json_decode(http_get($url), true);
        $openId       = $res['data']['open_id'];
        $access_token = $res['data']['access_token'];
        $url          = 'https://open.douyin.com/oauth/userinfo/?access_token=' . $access_token . '&open_id=' . $openId;
        $userInfo     = json_decode(http_get($url), true);
        if ($userInfo['message'] == 'success') {
            echo '<br/>昵称：' . $userInfo['data']['nickname'];
            echo '<br/>头像：<img src="' . $userInfo['data']['avatar'] . '" height="100"/>';
            echo '<br/>open_id：' . $userInfo['data']['open_id'];
            echo '<br/>union_id：' . $userInfo['data']['union_id'];
        }
        //开始上传视频
        //header( "Content-type:video/mp4");
        $PSize = filesize('/data/wwwroot/default/upload/test/003UWSaDlx07x4ZX44Ra01041201KtEq0E010.mp4');
        $videodata = fread(fopen('/data/wwwroot/default/upload/test/003UWSaDlx07x4ZX44Ra01041201KtEq0E010.mp4', "r"), $PSize);
        $postUrl = 'https://open.douyin.com//video/upload/';
        $res = https_post($postUrl,array('open_id'=>$openId,'access_token'=>$access_token,'video'=>$videodata));
        print_r($res);exit;
        }

    }






}