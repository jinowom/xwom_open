<?php
namespace backend\controllers;

use common\models\User;
use common\utils\ToolUtil;
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
                        'actions' => ['login', 'error','test','mp4'],
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
        $this->layout = 'main';
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
            return ToolUtil::returnAjaxMsg(false,ToolUtil::getSelectType($modelError,'password',''));
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
}
