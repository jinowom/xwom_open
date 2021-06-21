<?php
/**
 * Created by PhpStorm.
 * User: WANGWEIHUA
 * Date: 2019/8/4
 * Time: 11:14
 */

namespace api\modules\v1\controllers;

use yii\filters\ContentNegotiator;
use common\traits\BaseTraits;
use yii\web\Controller;
use yii\web\Response;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use backend\modules\common\models\ConfigPageManage;
use common\models\config\ConfigIpmanage;
use services\ErrorService;
use services\ResponseService;

class BaseController extends Controller
{
    //继承公共方法
    use BaseTraits;
    protected $except = []; // 排查一些方法不需要验证
    protected $pageSize;//每页多少条
    protected $sidx = 'id';//排序的字段
    protected $sord = 'desc';//正序或倒序

    public function init()
    {
        parent::init();
    }

    

    /**
     * 根据分页配置获取对应数量
     * @return int|mixed
     */
    public function getPage()
    {
        $page = ConfigPageManage::find()->select('num')->andWhere(['is_del'=>0,'type'=>2,'status'=>1])
            ->andWhere(['controller'=>Yii::$app->controller->id])
            ->andWhere(['action'=>Yii::$app->controller->action->id])
            ->asArray()->one();

        if(!empty($page)){
            $this->pageSize = $page['num'];
        }else{
            $this->pageSize = 10;
        }

        return $this->pageSize;
    }



    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $headers = Yii::$app->getRequest()->getHeaders();
        $AuthKey = $headers->get('auth-key', '');
        if (empty($AuthKey)) {
            throw new \yii\web\UnauthorizedHttpException(ErrorService::NO_OPERATION_PERMISSIONS, '403');
        }

        $key = getVar('auth-key', 3);

        if ($AuthKey != $key) {
            // throw new \yii\web\UnauthorizedHttpException('对不起，认证未通过', '403');
            throw new \yii\web\UnauthorizedHttpException(ErrorService::PARAMETER_ERROR, '203');
        }

        //判断header中的token
         $headers = \Yii::$app->getRequest()->getHeaders();
//        $ip = $headers->get('ip', '');
        $ip = \Yii::$app->request->getUserIP();

        // todo
        if (empty($ip)) {
            throw new \yii\web\UnauthorizedHttpException(ErrorService::IP_ERROR, '325');
        }
        $count = ConfigIpmanage::find()->from(ConfigIpmanage::tableName())->Where(['is_del' => 0, 'status' => 1, 'ip' => $ip])->count();
        if ($count > 0) {
            throw new \yii\web\UnauthorizedHttpException(ErrorService::IP_DISABLE, '326');
        }

        return true;
    }

    /**
     * 返回json格式
     * @param $code int 状态码
     * @param $data array 数据
     * @return array
     * @author rjl
     */
    public function message($code, $message = '', $data)
    {
        header('Content-type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers:Authkey');

        \Yii::$app->response->format = Response::FORMAT_JSON;
        $message = [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
        return $message;
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
     * 获取数据设置分页
     * @param $query
     * @param $offset
     * @return Response
     */
    public function getPageSize($query, $offset)
    {
        $listQuery = clone $query;
        $list = $listQuery->offset($offset)
            ->limit($this->pageSize)
            ->orderBy("$this->sidx $this->sord")->asArray()
            ->all();

        return $list;
    }

}