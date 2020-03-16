<?php
/**
 * Created by PhpStorm.
 * User: WANGWEIHUA
 * Date: 2019/8/4
 * Time: 11:14
 */

namespace backend\controllers;

use common\models\auth\AdminAuthRelation;
use common\models\User;
use common\traits\BaseTraits;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\UnauthorizedHttpException;

class BaseController extends Controller
{
    //继承公共方法
    use BaseTraits;

    protected $actions = ['*']; //需要验证的方法
    protected $except = []; // 排查一些方法不需要验证
    protected $mustlogin = []; // 必须要验证的
    protected $verbs = []; // 指定该规则用于匹配哪种请求方法（例如GET，POST）。 这里的匹配大小写不敏感。

    private $pageSize;//每页多少条
    private $page = 1;//第几页
    private $offset = 0;
    protected $sidx = 'id';//排序的字段
    protected $sord = 'desc';//正序或倒序

    protected $user_id;
    protected $dep_id;
    protected $site_id;
    protected $unit_id;
    protected $team_id;
    protected $app_id;

    public function init()
    {
        $userModel = new User();
        $this->pageSize = $this->post('limit',\Yii::$app->params['pageSize']);
        $this->page = $this->post('page', 1);
        $this->offset = ($this->page - 1) * $this->pageSize;
        $sidx = $this->post('sidx', $this->sidx);
        $this->sidx = empty($sidx) ? $this->sidx : $sidx;
        $sord = $this->post('sord', $this->sord);
        $this->sord = empty($sord) ? $this->sord : $sord;
        $this->user_id = self::GetUserId();
        $this->team_id = $userModel->getdataByUser($this->user_id,AdminAuthRelation::TYPE_TEAM);
        $this->dep_id = $userModel->getdataByUser($this->user_id,AdminAuthRelation::TYPE_DEP);
        $this->unit_id = $userModel->getdataByUser($this->user_id);
        $this->site_id = $userModel->getdataByUser($this->user_id,AdminAuthRelation::TYPE_SITE);
        $this->app_id = $userModel->getdataByUser($this->user_id,AdminAuthRelation::TYPE_APP);
        parent::init();
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => $this->actions,
                'except' => $this->except,
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => empty($this->mustlogin) ? [] : $this->mustlogin,
                        'roles' => ['?'], // guest
                    ],
                    [
                        'allow' => true,
                        'actions' => empty($this->mustlogin) ? [] : $this->mustlogin,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => $this->verbs,
            ],
        ];
    }

    public function beforeAction($action){
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['/site/login']);
        }
        if (!parent::beforeAction($action)) {
            return false;
        }
        $moduleName = \Yii::$app->controller->module->id;
        //如果是默认模块 不需要加载模块名称
        if ($moduleName == \Yii::$app->id) {
            $theUrl = \Yii::$app->controller->id . '/' . \Yii::$app->controller->action->id;
        } else {
            $theUrl = \Yii::$app->controller->module->id . '/' . \Yii::$app->controller->id . '/' . \Yii::$app->controller->action->id;
        }
        if(ArrayHelper::isIn($theUrl,$this->except)){
            return true;
        }

        if (\Yii::$app->user->can($theUrl)) {
            return true;
        }
        if(\Yii::$app->request->isAjax){
            return true;
        }
        //如果没有权限
        throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限','403');
//        return '没有权限';
    }

    /**
     * @Function 获取Get数据
     * @Author Weihuaadmin@163.com
     * @param null $param
     * @param null $defaultValue
     * @return array|mixed
     */
    public function get($param = null, $defaultValue = null){
        return \Yii::$app->request->get($param,$defaultValue);
    }

    /**
     * @Function 获取Post数据
     * @Author Weihuaadmin@163.com
     * @param null $param
     * @param null $defaultValue
     * @return array|mixed
     */
    public function post($param = null, $defaultValue = null){
        return \Yii::$app->request->post($param,$defaultValue);
    }

    /**
     * @Function: 获取table格式的列表数据
     * @Date: 2019/1/11 14:01
     * @param $query
     * @param string $dealFunction
     * @return array
     * @update Weihuaadmin@163.com  修改公共方法 查询结果是否为对象类型 1是 0否
     */
    public function getJqTableData($query, $dealFunction = '', $isObj = 0)
    {
        //获取处理列表数据
        $listQuery = clone $query;
        if(empty($isObj)){
            $listQuery->asArray();
        }
        $list = $listQuery ->offset($this->offset)
            ->limit($this->pageSize)
            ->orderBy("$this->sidx $this->sord")
            ->all();
        if (!empty($dealFunction)) {
            $list = $dealFunction($list);
        }
        //获取总数
        $count = $query->count();
        return $this->asJson([
            'code' => 0,
            'msg' => '',
            'count' => (int)$count,
            'data' => $list,
        ]);
    }
     
}