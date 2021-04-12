<?php
/**
 * Created by PhpStorm.
 * User: WANGWEIHUA
 * Date: 2019/8/4
 * Time: 11:14
 */

namespace backend\controllers;

use backend\actions\UploadAction;
use backend\actions\XpaperAction;
use  backend\modules\common\models\ConfigPageManage;
use common\models\auth\AdminAuthRelation;
use common\models\auth\AuthItem;
use common\models\User;
use common\traits\BaseTraits;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use common\helpers\Zip;
use yii\web\UploadedFile;
use Yii;

class BaseController extends Controller
{
    //继承公共方法
    use BaseTraits;

    protected $userInfo;

    protected $actions = ['*']; //需要验证的方法
    protected $except = []; // 排查一些方法不需要验证
    protected $mustlogin = []; // 必须要验证的
    protected $verbs = []; // 指定该规则用于匹配哪种请求方法（例如GET，POST）。 这里的匹配大小写不敏感。

    protected $pageSize;//每页多少条
    protected $page = 1;//第几页
    protected $sidx = 'id';//排序的字段
    protected $sord = 'desc';//正序或倒序

    protected $user_id;
    protected $dep_id;
    protected $site_id;
    protected $unit_id;
    protected $team_id;
    protected $app_id;

    //规则
    protected $uploadFileName = 'file'; //上传的表单名称
    protected $limitRules = []; //附件的限制规则
    protected $is = 1; //是否入库


    public function init(){
        $userModel = new User();
        $this->pageSize = $this->post('pageSize', 10);
        $this->page = $this->post('page', 1);
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
        $authManager = \Yii::$app->getAuthManager();
        $permissions = $authManager->getPermissionsByUser($this->user_id);
        $permissions = ArrayHelper::toArray($permissions);
        $this->userInfo = $permissions;
        parent::init();
    }

    //获取分页数量
    public function getPage(){
        $page = ConfigPageManage::find()->select('num')->andWhere(['is_del'=>0,'type'=>2,'status'=>1])
            ->andWhere(['controller'=>Yii::$app->controller->id])
            ->andWhere(['action'=>Yii::$app->controller->action->id])
            ->asArray()->one();
        if(!empty($page)){
            $this->pageSize = $page['num'];
        }else{
            $this->pageSize = 10;
        }
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
            return $this->redirect(['/site/login'])->send();
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

    public function getFieUrl($fileUrl=""){
        if(empty($fileUrl)){
            return "该文件目录不正确";
        }
        $suffix = substr(strrchr($fileUrl, '.'), 1);
        //判断该文件属于哪个类型
        if(Zip::isImage($suffix)){
            $fileType = "image";
        }else if(Zip::isVideo($suffix)){
            $fileType = "video";
        }else{
            $fileType = 'file';
        }
        return \Yii::$app->controller->module->id.'/'.\Yii::$app->controller->id.'/'.$fileType.'/'.date("Ymd",time());
    }

    public function actions()
    {
        return [
            //绑定上传功能
            'upload' => [
                'class' => UploadAction::className(),
                'files' => UploadedFile::getInstanceByName($this->uploadFileName),
                'limitRules' => $this->limitRules,
                'is' => $this->is,
            ],
            //通过报纸获取期次和版面信息
            'issueandpage' => [
                'class' => XpaperAction::className(),
                'pId' => $this->get('pId'),
            ]
        ];
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
        $list = $listQuery ->offset(($this->page - 1) * $this->pageSize)
            ->limit($this->pageSize)
            ->orderBy("$this->sidx $this->sord")
            ->all();
        if (!empty($dealFunction)) {
            $list = $dealFunction($list);
        }
        foreach ($list as $key => $value) {
            if(!empty($value['updated_at'])){
                $list[$key]['updated_at'] = date('Y-m-d H:i',$value['updated_at']);
            }
            if(!empty($value['created_at'])){
                $list[$key]['created_at'] = date('Y-m-d H:i',$value['created_at']);
            }
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

    public static function getMenuName(){
        $module = \Yii::$app->controller->module->id;
        $controller = \Yii::$app->controller->id;
        $action = \Yii::$app->controller->action->id;
        $urlRoute = str_replace(\Yii::$app->id.'/','',$module.'/'.$controller.'/'.$action);
        return AuthItem::findValueByWhere(['name' => $urlRoute],['description'],['name'=>SORT_DESC]);
    }

    public function returnSuccess($data , $msg = 'ok'){
        $this->asJson(['code' => 200,'message' => $msg,'data' => $data]);
    }

    public function returnError($data = '', $msg = 'auth_error'){
        $this->asJson(['code' => 400,'message' => $msg,'data' => $data]);
    }
     
}