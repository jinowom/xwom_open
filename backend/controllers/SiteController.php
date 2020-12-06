<?php
namespace backend\controllers;

use common\models\User;
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
                        'actions' => ['login', 'error','captcha','entr'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
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

        //验证
        if ($model->load($postData,'') && $model->login()) {
            return ToolUtil::returnAjaxMsg(true,'登录成功',[
                'goBack' => Url::to(['index/index'])
            ]);
        } else {
            $modelError = $model->getFirstErrors();
            $modelError = end($modelError);
            return ToolUtil::returnAjaxMsg(false,$modelError);
        }
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
