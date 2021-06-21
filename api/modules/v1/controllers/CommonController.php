<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
/**
 * Site controller
 */
class CommonController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),[
            //todo 认证
            // 'authenticatior' => [
            //     'class' => QueryParamAuth::className()
            // ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
	 * 在一段字符串里增加域名
	 * @param  string $str 字符串
	 * @param  string $find 加入的位置
	 * @param  string $substr_s 在什么地方插入
	 * @param  string $domain 添加的域名
	 */
    function add_domain_url_str($str, $find = 'src="/', $substr_s = '/')
    {
        $domain = getVar('FILEURL');
        $count = strpos($str, $find);
        if (!$count) {
            return $str;
        }
        $strlen = strlen($find);
        $strat = explode($substr_s, $find);
        $strs = substr_replace($str, $strat[0] . $domain . $strat[1], $count, $strlen);

        //查看还有没有
        return $this->add_domain_url_str($strs, $find, $substr_s, $domain);
    }

}
