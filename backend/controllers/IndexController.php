<?php
/**
 * Created by PhpStorm.
 * User: WANGWEIHUA
 * Date: 2019/8/4
 * Time: 17:05
 */

namespace backend\controllers;

use Yii;
use common\helpers\FileHelper;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\User;
use common\models\VerifyCodeHistory;
use common\utils\ToolUtil;
use yii\helpers\ArrayHelper;


class IndexController extends BaseController
{
    protected $except = ['index/index','index/welcome','index/update-self-data','index/update-pwd'];

    /**
     * @Function
     * @Author Weihuaadmin@163.com
     * @return string
     */
    public function actionIndex(){
        $authManager = \Yii::$app->getAuthManager();
        $permissions = $authManager->getPermissionsByUser($this->user_id);
        $permissions = ArrayHelper::toArray($permissions);
        $permissions = ToolUtil::arrToTree($permissions,null);
        $permissions = ToolUtil::menuListHtml($permissions);
        return $this->render('index',[
            'permissions' => $permissions
        ]);
    }


    /**
     * @Function 后台首页
     * @Author Weihuaadmin@163.com
     * @return string
     */
    public function actionWelcome(){
        // 禁用函数
        $disableFunctions = ini_get('disable_functions');
        $disableFunctions = !empty($disableFunctions) ? explode(',', $disableFunctions) : '未禁用';
        // 附件大小
        $attachmentSize = FileHelper::getDirSize(Yii::getAlias('@attachment'));
        return $this->render('welcome', [
            'mysql_size' => Yii::$app->services->backend->getDefaultDbSize(),
            'attachment_size' => $attachmentSize ?? 0,
            'disable_functions' => $disableFunctions,
        ]);
    }

    /**
     * @Function 修改个人资料
     * @Author Weihuaadmin@163.com
     */
    public function actionUpdateSelfData(){
        $request = \Yii::$app->request;
        if($request->isPost){
            $userModel = new User();
            return $userModel->updateData($this->post(),$this->user_id);
        }
        $userInfo = \Yii::$app->getUser()->identity;
        return $this->render('update',[
            'userInfo' => $userInfo
        ]);
    }

    /**
     * @Function 修改密码
     * @Author Weihuaadmin@163.com
     * @return string
     */
    public function actionUpdatePwd(){
        $request = \Yii::$app->request;
        if($request->isPost){
            //验证原密码
            $postData = $this->post('user');
            $password = $postData['L_pass'];
            $pass = $postData['pass'];
            $password_hash = \Yii::$app->getUser()->identity->password_hash;
            if(\Yii::$app->security->validatePassword($password, $password_hash)){
                $newPass = \Yii::$app->getSecurity()->generatePasswordHash($pass);
                $updateRes = User::updateAll(['password_hash' => $newPass], "user_id = :user_id", [":user_id" => $this->user_id]);
                if($updateRes){
                    return ToolUtil::returnAjaxMsg(true,'修改成功');
                }
            }else{
                return ToolUtil::returnAjaxMsg(false,'原密码不正确');
            }
            return ToolUtil::returnAjaxMsg(false,'修改失败');
        }
        return $this->render('updatePwd');
    }

    /**
     * @Function 发送短信
     * @param  $phone 接收手机号
     * @Author Weihuaadmin@163.com
     * @return sendSms
     */
    public function actionSendSms(){
        $phone = $this->post('phone');
        return $this->asJson(VerifyCodeHistory::sendSMS($phone,\Yii::$app->params['SMS']['MODIFY_CODE']));
    }

}