<?php
namespace backend\controllers;

use common\models\{Admin,user};
use common\models\wechat\OuterMp;
use common\utils\ToolUtil;
use common\utils\WechatUtil;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','captcha','entr','update-pwd'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'update-pwd'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post','get'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'backColor'=>0x000000,//背景颜色
                'maxLength' => 6, //最大显示个数
                'minLength' => 6,//最少显示个数
                'padding' => 5,//间距
                'height'=> 50,//高度
                'width' => 150,  //宽度
                'foreColor'=>0xffffff,     //字体颜色
                'offset' => 6,
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }
    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->redirect(Url::to(['index/index']));
        /*echo '登录成功';
        $Logout = Url::to(['site/logout']);
        echo "<a href='{$Logout}'>退出</a>";
        exit;
        return $this->render('index');*/
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = 'layouts';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $postData = Yii::$app->request->post();
        if(!Yii::$app->request->isPost){
            $model->password = '';
            return $this->render('login', [
                'model' => $model,
            ]);
        }
        $UserModel = Admin::findOne(['username'=>$postData['username']]);
        if($UserModel->login_count <= 0 ){
            return ToolUtil::returnAjaxMsg('403','初始账户请重新设置密码',['user_id'=>$UserModel->user_id]);
        }
        if(time() <= $UserModel->allow_login_time ){
                return ToolUtil::returnAjaxMsg(false,'登录失败次数过多，账号已锁定，请在'.date('Y-m-d H:i:s',$UserModel->allow_login_time).'之后重新登录');
            }
        //验证
        if ($model->load($postData,'') && $model->login()) {

                $UserModel->login_count = $UserModel->login_count + 1;
                $UserModel->error_count = 0;
                $UserModel->allow_login_time = 0;
                $UserModel->login_time = time();
                $UserModel->login_ip = Yii::$app->request->getUserIP();
                $UserModel->save();
                return ToolUtil::returnAjaxMsg(true,'登录成功',[
                    'goBack' => Url::to(['index/index'])
                ]);
        } else {
            $modelError = $model->getFirstErrors();
            if(!empty($UserModel) && empty($modelError['verifyCode'])){
                if($UserModel->error_count >= 2 ){
                    $UserModel->error_count = 0;
                    $UserModel->allow_login_time = time()+10*60;
                }else{
                    $UserModel->error_count = $UserModel->error_count + 1;
                }
                $UserModel->save();
            }
            $modelError = end($modelError);
            return ToolUtil::returnAjaxMsg(false,$modelError);
        }
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
            $postData = $request->post('user');
            $user_id = isset($postData['user_id'])?$postData['user_id']:"";
            $password = $postData['L_pass'];
            $pass = $postData['pass'];
            $UserModel = Admin::findOne(['user_id'=>$user_id]);
            $password_hash = $UserModel->password_hash;
            if(\Yii::$app->security->validatePassword($password, $password_hash)){
                $newPass = \Yii::$app->getSecurity()->generatePasswordHash($pass);
//                $updateRes = User::updateAll(['password_hash' => $newPass,'login_count'=>1], "user_id = :user_id", [":user_id" => $user_id]);//在User模型中，没找到此User::updateAll方法
                $UserModel->password_hash = $newPass;
                $UserModel->login_count = 1;
                $UserModel->save();
                //if($updateRes){
                if($UserModel){
                    return ToolUtil::returnAjaxMsg(true,'修改成功');
                }
            }else{
                return ToolUtil::returnAjaxMsg(false,'原密码不正确');
            }
            return ToolUtil::returnAjaxMsg(false,'修改失败');
        }
        $user_id = isset($_GET['user_id'])?$_GET['user_id']:"";
        return $this->render('update-pwd',['user_id'=>$user_id]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }



    /**
     * @Function 验证公众号
     * @Author Weihuaadmin@163.com
     */
    public function actionEntr(){
        $request = \Yii::$app->request;
        $mid = $request -> get('id');
        $echostr = $request -> get('echostr');
        $signature = $request -> get('signature');
        $nonce = $request -> get('nonce');
        if(empty($echostr) && empty($signature) && empty ($nonce)){
            return ToolUtil::returnAjaxMsg(false,'Access denied');
        }
        if(empty($mid)){
            return ToolUtil::returnAjaxMsg(false,'Access denied');
        }
        $mpInfo = OuterMp::findValueByWhere(['id' => $mid],[],[]);
        $options = [
            'appid' => $mpInfo['appid'],
            'appsecret' => $mpInfo['appsecret'],
            'token' => $mpInfo['valid_token'],
            'encodingaeskey' => $mpInfo['encodingaeskey']
        ];
        if (!empty($_GET['echostr']) && !empty($_GET["signature"])) {
            $weObj = new WechatUtil($options);
            $return = $weObj ->valid(true);
            if($return){
                if ($mpInfo['valid_status'] == 0) {
                    OuterMp::updateAll(['valid_status' => 1],"id=:id",[":id" => $mid]);
                }
                echo $echostr;
            }
            exit;
        }
    }
}
