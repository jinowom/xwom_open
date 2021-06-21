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
use common\models\auth\{AuthItemChild};
use common\models\config\{ConfigMessageMap};
use common\models\{VerifyCodeHistory,User};
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
        $permissionsList = ToolUtil::arrToTree($this->userInfo,null);
        $mesCount = ConfigMessageMap::find()->select('user_id')->andWhere(['is_del'=>0,'user_type'=>2])
                                                ->andWhere(['user_id'=>$this->user_id,'is_read'=>0])->count();
        return $this->render('index',[
            'permissionsList' => $permissionsList,
            'mesCount' => $mesCount
        ]);
    }


    /**
     * @Function 系统探针
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

    /**
     * @Function 工作台
     */
    public function actionDashboard(){
        $name = $this->get('name','');
        $data =  $this->findItem($name);
        if (Yii::$app->request->isAjax) {
           $this->returnSuccess($data,'success');
        }else{
            switch ($name) {
                case 'XportalSystem_00':
                    return $this->render('dashboard/xportal-dashboard',['data'=>$data]);
                    break;
                case 'XwomSystem_00':
                    return $this->render('dashboard/xwom-dashboard',['data'=>$data]);
                    break;
                default:
                    return $this->render('dashboard',['data'=>$data]);
                    break;
            }
        }
    }

    //获取子菜单
    public function findItem($name){
        $role = User::find()->select('role_id')->Where(['user_id'=>$this->user_id])->asArray()->one()['role_id'];
        $role = explode(',',$role);
        $data = AuthItemChild::find()->from(AuthItemChild::tableName() . ' Child')
                             ->select('item.name,item.order_sort,item.description,item.icon')
                             ->leftJoin('auth_item as item','item.name = Child.child')
                             ->andWhere(['in','Child.parent',$role])
                             ->andWhere(['item.parent_name' => $name,'status'=>1,'is_menu'=>1])
                             ->orderBy("item.order_sort desc")
                             ->limit(6)
                             ->asArray()->all();
        return $data;
    }

}