<?php

namespace api\modules\v2\controllers;

use common\models\User;
use api\models\XportalMember;
use services\ErrorService;
use services\ResponseService;
use services\SendSmsService;
use common\models\VerifyCodeHistory;
use backend\modules\xportal\models\XportalMemberFeedback;
use Yii;

/**
 * Site controller
 */
class UserController extends BaseController
{
    public $modelClass = 'common\models\User';

    //todo 注册和登录不需要验证token
    /*public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),[
            'authenticatior' => [
                'class' => QueryParamAuth::className()
            ]
        ]);
    }*/


    /**
     * API注册
     */
    public function actionSignup()
    {
        userLog(3, 3, 'API注册');
        $model                  = new XportalMember();
        $request['member_user'] = $this->post('username');
        $pwd                    = $this->post('password');

        if (empty($request['member_user'])) {
            return ResponseService::response(ErrorService::USER_NAME_EMPTY);
        }
        if (empty($pwd)) {
            return ResponseService::response(ErrorService::PASSWORD_EMPTY);
        }

        //密码必须为8-20位
        if (strlen($pwd) > 20 || strlen($pwd) < 8) {
            return ResponseService::response(ErrorService::PASSWORD_LENGTH_ERROR);
        }
        //必须是数字和字母组合
        if (!preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)/', $pwd)) {
            return ResponseService::response(ErrorService::PASSWORD_COMBINATION_ERROR);
        }

        $request['member_check_status'] = $model::MEMBER_CHECK_STATUS;
        $request['member_pwd']          = $model->setPassword($pwd);
        // $request['token'] = $model->generateAccessToken();

        $res = XportalMember::createDo($request);
        if ($res === true) {
            return ResponseService::response(ErrorService::STATUS_SUCCESS);
        } else {
            if (!empty($res['member_user'])) {
                return ResponseService::response(ErrorService::MEMBER_NAME_ERROR);
            } else {
                return ResponseService::response(ErrorService::STATUS_FAILED);
            }
        }
    }

    /**
     * API登录
     * @return array
     */
    public function actionLogin()
    {
        userLog(3, 2, 'API登录');
        $model = new XportalMember();
        //使用getBodyParams处理POST请求
        $type = $this->post('type', null);
        //查询类型是否存在
        if (!in_array($type, [1, 2])) {
            return ResponseService::response(ErrorService::TYPE_ERROR);
        }
        if ($type == 1) {
            $user = $this->post('username');
            $pwd  = $this->post('password');
            if (empty($user)) {
                return ResponseService::response(ErrorService::USER_NAME_EMPTY);
            }
            if (empty($pwd)) {
                return ResponseService::response(ErrorService::PASSWORD_EMPTY);
            }

            $getUser = $model::findOne(['member_user' => $user, 'is_del' => 0, 'member_check_status' => 1]);
            if (empty($getUser)) {
                return ResponseService::response(ErrorService::MEMBER_EMPTY);
            }

            $validatePassword = \Yii::$app->security->validatePassword($pwd, $getUser->member_pwd);
            if (!$validatePassword) {
                if ($getUser->error_count >= 2) {
                    $getUser->error_count      = 0;
                    $getUser->allow_login_time = time() + 10 * 60;
                } else {
                    $getUser->error_count = $getUser->error_count + 1;
                }
                $getUser->save();
                return ResponseService::response(ErrorService::ACCOUNT_PASSWORD_ERROR);
            }

        } elseif ($type == 2) {
            $mobile = $this->post('mobile');
            $code   = $this->post('code');
            if (empty($mobile)) {
                return ResponseService::response(ErrorService::MOBILE_EMPTY);
            }
            if (empty($code)) {
                return ResponseService::response(ErrorService::CODE_EMPTY);
            }

            $getUser = $model::findOne(['member_mobile' => $mobile, 'is_del' => 0, 'member_check_status' => 1]);
            if (empty($getUser)) {
                return ResponseService::response(ErrorService::MEMBER_EMPTY);
            }

            $verifyRes = VerifyCodeHistory::verifySms($mobile, $code, VerifyCodeHistory::CODE_TYPE_LOGIN);
            if (!$verifyRes['status']) {
                if ($getUser->error_count >= 2) {
                    $getUser->error_count      = 0;
                    $getUser->allow_login_time = time() + 10 * 60;
                } else {
                    $getUser->error_count = $getUser->error_count + 1;
                }
                $getUser->save();
                return ResponseService::apiResponse(ErrorService::STATUS_FAILED, $verifyRes['msg']);
            }
        }

        if (time() <= $getUser->allow_login_time) {
            $msg = '登录失败次数过多，账号已锁定，请在' . date('Y-m-d H:i:s', $getUser->allow_login_time) . '之后重新登录';
            return ResponseService::apiResponse(ErrorService::STATUS_FAILED, $msg);
        }

        $request['member_id']     = $getUser->member_id;
        $request['token']         = $model->generateAccessToken();
        $request['token_exptime'] = time() + 3600 * 7 * 24;

        $request['login_count']      = $getUser->login_count + 1;
        $request['allow_login_time'] = 0;
        $request['login_time']       = time();
        $request['login_ip']         = Yii::$app->request->getUserIP();

        $res = XportalMember::updateDo($request);
        if ($res === true) {
            $data['access_token'] = $request['token'];
            return ResponseService::response(ErrorService::STATUS_SUCCESS, $data);
        } else {
            return ResponseService::response(ErrorService::STATUS_FAILED);
        }
    }

    /**
     * 发送短信
     * @Author rjl
     * @return array
     */
    public function actionSendSms()
    {
        $mobile = $this->post('mobile');
        if (empty($mobile)) {
            return ResponseService::response(ErrorService::MOBILE_EMPTY);
        }

        $type = $this->post('type');
        if (empty($type)) {
            return ResponseService::response(ErrorService::TYPE_EMPTY);
        }

        if (!preg_match("/^1[34578]\d{9}$/", $mobile)) {
            return ResponseService::response(ErrorService::PARAMETER_ERROR);
        }
        $send = SendSmsService::getCodeSms($mobile, $type);
        if ($send['code'] == 200) {
            return ResponseService::response(ErrorService::STATUS_SUCCESS);
        }
        return ResponseService::apiResponse(ErrorService::STATUS_FAILED, $send['message']);
    }

    /**
     * 修改密码
     */
    public function actionUpdatePassword()
    {
        $model = new XportalMember();
        //判断header中的token
        $headers = \Yii::$app->getRequest()->getHeaders();
        $token   = $headers->get('access-token', '');
        $mobile  = $this->post('mobile');
        $code    = $this->post('code');
        if (empty($mobile)) {
            return ResponseService::response(ErrorService::MOBILE_EMPTY);
        }
        if (empty($code)) {
            return ResponseService::response(ErrorService::CODE_EMPTY);
        }
        if (empty($token)) {
            return ResponseService::response(ErrorService::TOKEN_EMPTY);
        }

        $verifyRes = VerifyCodeHistory::verifySms($mobile, $code, VerifyCodeHistory::CODE_TYPE_UPDATE);
        if (!$verifyRes['status']) {
            return ResponseService::apiResponse(ErrorService::STATUS_FAILED, $verifyRes['msg']);
        }

        //该用户未绑定手机号，请联系管理员
        $getMobile = $model::findOne(['token' => $token, 'is_del' => 0, 'member_check_status' => 1]);
        if (empty($getMobile->member_mobile)) {
            return ResponseService::response(ErrorService::BINDING_NO_ERROR);
        }

        $getUser = $model::findOne(['member_mobile' => $mobile, 'token' => $token, 'is_del' => 0, 'member_check_status' => 1]);
        if (empty($getUser)) {
            return ResponseService::response(ErrorService::MEMBER_EMPTY);
        }

        $pwd = $this->post('password');
        if (empty($pwd)) {
            return ResponseService::response(ErrorService::PASSWORD_EMPTY);
        }

        //密码必须为6-30位
        if (strlen($pwd) > 30 || strlen($pwd) < 6) {
            return ResponseService::response(ErrorService::PASSWORD_LENGTH_ERROR);
        }
        //必须是数字和字母组合
        if (!preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)/', $pwd)) {
            return ResponseService::response(ErrorService::PASSWORD_COMBINATION_ERROR);
        }

        $request['member_id']  = $getUser->member_id;
        $request['member_pwd'] = $model->setPassword($pwd);

        $res = XportalMember::updateDo($request);
        if ($res === true) {
            return ResponseService::response(ErrorService::STATUS_SUCCESS);
        } else {
            return ResponseService::response(ErrorService::STATUS_FAILED);
        }
    }


    /**
     * 忘记密码
     */
    public function actionForgotPassword()
    {
        $model    = new XportalMember();
        $username = $this->post('username');
        $mobile   = $this->post('mobile');
        $code     = $this->post('code');
        if (empty($mobile)) {
            return ResponseService::response(ErrorService::MOBILE_EMPTY);
        }
        if (empty($code)) {
            return ResponseService::response(ErrorService::CODE_EMPTY);
        }
        if (empty($username)) {
            return ResponseService::response(ErrorService::USER_NAME_EMPTY);
        }

        $verifyRes = VerifyCodeHistory::verifySms($mobile, $code, VerifyCodeHistory::CODE_TYPE_PASSWORD);
        if (!$verifyRes['status']) {
            return ResponseService::apiResponse(ErrorService::STATUS_FAILED, $verifyRes['msg']);
        }

        $getMobile = $model::findOne(['member_user' => $username, 'is_del' => 0, 'member_check_status' => 1]);

        if (empty($getMobile->member_mobile)) {
            return ResponseService::response(ErrorService::BINDING_NO_ERROR);
        }
        $getUser = $model::findOne(['member_mobile' => $mobile, 'member_user' => $username, 'is_del' => 0, 'member_check_status' => 1]);
        if (!empty($getUser)) {
            $data = ['access_token' => $getUser->token];
            return ResponseService::response(ErrorService::STATUS_SUCCESS, $data);
        }

        return ResponseService::response(ErrorService::STATUS_FAILED);
    }

    /**
     * 忘记密码确认重置
     */
    public function actionConfirmPassword()
    {
        $model = new XportalMember();
        //判断header中的token
        $headers = \Yii::$app->getRequest()->getHeaders();
        $token   = $headers->get('access-token', '');
        if (empty($token)) {
            return ResponseService::response(ErrorService::TOKEN_EMPTY);
        }

        $getUser = $model::findOne(['token' => $token, 'is_del' => 0, 'member_check_status' => 1]);
        if (empty($getUser)) {
            return ResponseService::response(ErrorService::MEMBER_EMPTY);
        }
        $pwd = $this->post('password');
        if (empty($pwd)) {
            return ResponseService::response(ErrorService::PASSWORD_EMPTY);
        }

        //密码必须为6-30位
        if (strlen($pwd) > 30 || strlen($pwd) < 6) {
            return ResponseService::response(ErrorService::PASSWORD_LENGTH_ERROR);
        }
        //必须是数字和字母组合
        if (!preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)/', $pwd)) {
            return ResponseService::response(ErrorService::PASSWORD_COMBINATION_ERROR);
        }

        $request['member_id']  = $getUser->member_id;
        $request['member_pwd'] = $model->setPassword($pwd);

        $res = XportalMember::updateDo($request);
        if ($res === true) {
            return ResponseService::response(ErrorService::STATUS_SUCCESS);
        } else {
            return ResponseService::response(ErrorService::STATUS_FAILED);
        }
    }

    /**
     * 用户反馈接口
     */
    public function actionFeedback()
    {
        //判断header中的token
        $headers = \Yii::$app->getRequest()->getHeaders();
        $token   = $headers->get('access-token', '');
        if (empty($token)) {
            return ResponseService::response(ErrorService::NO_OPERATION_PERMISSIONS);
        }

        //通过token获取用户信息
        $getMember = XportalMember::findIdentityByAccessToken($token);
        if (empty($getMember)) {
            return ResponseService::response(ErrorService::MEMBER_EMPTY);
        }

        //判斷用戶的token是否过期
        if ($getMember['token_exptime'] < time()) {
            return ResponseService::response(ErrorService::TOKEN_OVERDUE);
        }

        $content = $this->post('content', null);
        if (empty($content)) {
            return ResponseService::response(ErrorService::CONTENT_EMPTY);
        }

        $model            = new XportalMemberFeedback();
        $model->member_id = $getMember['member_id'];
        $model->content   = $content;

        if ($model->save()) {
            return ResponseService::response(ErrorService::STATUS_SUCCESS);
        }
        return ResponseService::response(ErrorService::STATUS_FAILED);
    }

    /**
     * 修改用户昵称接口
     */
    public function actionNicknameUpdate()
    {
        //判断header中的token
        $headers = \Yii::$app->getRequest()->getHeaders();
        $token   = $headers->get('access-token', '');
        if (empty($token)) {
            return ResponseService::response(ErrorService::NO_OPERATION_PERMISSIONS);
        }

        //通过token获取用户信息
        $getMember = XportalMember::findIdentityByAccessToken($token);
        if (empty($getMember)) {
            return ResponseService::response(ErrorService::MEMBER_EMPTY);
        }

        //判斷用戶的token是否过期
        if ($getMember['token_exptime'] < time()) {
            return ResponseService::response(ErrorService::TOKEN_OVERDUE);
        }

        $name = $this->post('name', null);
        if (empty($name)) {
            return ResponseService::response(ErrorService::NICKNAME_EMPTY);
        }

        $getMember->member_name = $name;
        if ($getMember->save()) {
            return ResponseService::response(ErrorService::STATUS_SUCCESS);
        }
        return ResponseService::response(ErrorService::STATUS_FAILED);
    }

    /**
     * 绑定手机号接口与更换手机号
     * @return array
     */
    public function actionMobileReplace()
    {
        //判断header中的token
        $headers = \Yii::$app->getRequest()->getHeaders();
        $token   = $headers->get('access-token', '');
        if (empty($token)) {
            return ResponseService::response(ErrorService::NO_OPERATION_PERMISSIONS);
        }

        //通过token获取用户信息
        $getMember = XportalMember::findIdentityByAccessToken($token);
        if (empty($getMember)) {
            return ResponseService::response(ErrorService::MEMBER_EMPTY);
        }

        //判斷用戶的token是否过期
        if ($getMember['token_exptime'] < time()) {
            return ResponseService::response(ErrorService::TOKEN_OVERDUE);
        }

        $mobile = $this->post('mobile', null);
        if (empty($mobile)) {
            return ResponseService::response(ErrorService::MOBILE_EMPTY);
        }

        if ($getMember['member_mobile'] == $mobile) {
            return ResponseService::response(ErrorService::MOBILE_REPEAT);
        }

        $code = $this->post('code');
        if (empty($code)) {
            return ResponseService::response(ErrorService::CODE_EMPTY);
        }
        $verifyRes = VerifyCodeHistory::verifySms($mobile, $code, VerifyCodeHistory::CODE_TYPE_BINDING);
        if (!$verifyRes['status']) {
            return ResponseService::apiResponse(ErrorService::STATUS_FAILED, $verifyRes['msg']);
        }

        $request['member_id']     = $getMember['member_id'];
        $request['member_mobile'] = $mobile;

        $res = XportalMember::updateDo($request);
        if ($res === true) {
            return ResponseService::response(ErrorService::STATUS_SUCCESS);
        } else {
            $msg = json_decode($res, true);
            if (!empty($msg)) {
                return ResponseService::apiResponse(ErrorService::STATUS_FAILED, $msg['member_mobile'][0]);
            }
            return ResponseService::response(ErrorService::STATUS_FAILED, $msg['member_mobile'][0]);
        }
    }


    /**
     * 获取用户信息
     */
    public function actionGetMember()
    {
        //判断header中的token
        $headers = \Yii::$app->getRequest()->getHeaders();
        $token   = $headers->get('access-token', '');
        if (empty($token)) {
            return ResponseService::response(ErrorService::NO_OPERATION_PERMISSIONS);
        }

        //通过token获取用户信息
        $getMember = XportalMember::findIdentityByAccessToken($token);
        if (empty($getMember)) {
            return ResponseService::response(ErrorService::MEMBER_EMPTY);
        }

        //判斷用戶的token是否过期
        if ($getMember['token_exptime'] < time()) {
            return ResponseService::response(ErrorService::TOKEN_OVERDUE);
        }

        return ResponseService::response(ErrorService::STATUS_SUCCESS, $getMember);
    }

    /**
     * 解绑手机号接口
     * @return array
     */
    public function actionMobileUnlock()
    {
        //判断header中的token
        $headers = \Yii::$app->getRequest()->getHeaders();
        $token = $headers->get('access-token', '');
        if (empty($token)) {
            return ResponseService::response(ErrorService::NO_OPERATION_PERMISSIONS);
        }

        //通过token获取用户信息
        $getMember = XportalMember::findIdentityByAccessToken($token);
        if (empty($getMember)) {
            return ResponseService::response(ErrorService::MEMBER_EMPTY);
        }

        //判斷用戶的token是否过期
        if ($getMember['token_exptime'] < time()) {
            return ResponseService::response(ErrorService::TOKEN_OVERDUE);
        }

        $mobile = $this->post('mobile', null);
        if (empty($mobile)) {
            return ResponseService::response(ErrorService::MOBILE_EMPTY);
        }

        if ($getMember['member_mobile'] != $mobile) {
            return ResponseService::response(ErrorService::MOBILE_INCONFORMITY);
        }

        $code = $this->post('code');
        if (empty($code)) {
            return ResponseService::response(ErrorService::CODE_EMPTY);
        }
        $verifyRes = VerifyCodeHistory::verifySms($mobile,$code,VerifyCodeHistory::CODE_TYPE_UNLOCK);
        if(!$verifyRes['status']){
            return ResponseService::apiResponse(ErrorService::STATUS_FAILED, $verifyRes['msg']);
        }

        $request['member_id'] = $getMember['member_id'];
        $request['member_mobile'] = null;

        $res = XportalMember::updateDo($request);
        if ($res === true) {
            return ResponseService::response(ErrorService::STATUS_SUCCESS);
        } else {
            $msg = json_decode($res, true);
            if(!empty($msg)){
                return ResponseService::apiResponse(ErrorService::STATUS_FAILED, $msg['member_mobile'][0]);
            }
            return ResponseService::response(ErrorService::STATUS_FAILED, $msg['member_mobile'][0]);
        }
    }


}
