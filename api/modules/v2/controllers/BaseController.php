<?php
/**
 * Created by PhpStorm.
 * User: WANGWEIHUA
 * Date: 2019/8/4
 * Time: 11:14
 */

namespace api\modules\v2\controllers;
use Yii;
use yii\filters\ContentNegotiator;
use common\traits\BaseTraits;
use yii\web\Response;
use yii\rest\Controller;
use backend\modules\common\models\ConfigPageManage;
use common\models\config\ConfigIpmanage;

class BaseController extends Controller
{
    //继承公共方法
    use BaseTraits;
    protected $except = []; // 排查一些方法不需要验证
    protected $pageSize;//每页多少条
    protected $page = 1;//第几页
    protected $sidx = 'id';//排序的字段
    protected $sord = 'desc';//正序或倒序

    public function init()
    {
        $this->pageSize = $this->get('pageSize', 10);
        $this->page     = $this->get('page', 1);
        $sidx           = $this->get('sidx', $this->sidx);
        $this->sidx     = empty($sidx) ? $this->sidx : $sidx;
        $sord           = $this->get('sord', $this->sord);
        $this->sord     = empty($sord) ? $this->sord : $sord;

        parent::init();
    }


    /**
     * 根据分页配置获取对应数量
     * @return int|mixed
     */
    public function getPage()
    {
        $page = ConfigPageManage::find()->select('num')->andWhere(['is_del' => 0, 'type' => 2, 'status' => 1])
            ->andWhere(['controller' => Yii::$app->controller->id])
            ->andWhere(['action' => Yii::$app->controller->action->id])
            ->asArray()->one();

        if (!empty($page)) {
            $this->pageSize = $page['num'];
        } else {
            $this->pageSize = 10;
        }

        return $this->pageSize;
    }

    /**
     * 1.跨域调用
     * 2.返回json数据
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors'  => [
                // restrict access to
                'Access-Control-Request-Method'    => ['*'],
                // Allow only POST and PUT methods
                'Access-Control-Request-Headers'   => ['*'],
                // Allow only headers 'X-Wsse'
                'Access-Control-Allow-Credentials' => true,
                // Allow OPTIONS caching
                'Access-Control-Max-Age'           => 3600,
                // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                'Access-Control-Expose-Headers'    => ['X-Pagination-Current-Page'],
            ],
        ];

        $behaviors['contentNegotiator'] = [
            'class'   => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON
            ]
        ];
        return $behaviors;
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $headers = Yii::$app->getRequest()->getHeaders();
        $AuthKey = $headers->get('auth-key', '');
        if (empty($AuthKey)) {
            throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限', '403');
        }

        $key = getVar('auth-key', 3);
        if ($AuthKey != $key) {
            throw new \yii\web\UnauthorizedHttpException('对不起，认证未通过', '203');
        }

        //判断header中的token
        $ip = \Yii::$app->request->getUserIP();
        if (empty($ip)) {
            throw new \yii\web\UnauthorizedHttpException('IP为空', '325');
        }
        $count = ConfigIpmanage::find()->from(ConfigIpmanage::tableName())->Where(['is_del' => 0, 'status' => 1, 'ip' => $ip])->count();
        if ($count > 0) {
            throw new \yii\web\UnauthorizedHttpException('IP已被禁用', '326');
        }

        return true;
    }

    /**
     * @Function 获取Get数据
     * @param null $param
     * @param null $defaultValue
     * @return array|mixed
     */
    public function get($param = null, $defaultValue = null)
    {
        return \Yii::$app->request->get($param, $defaultValue);
    }

    /**
     * @Function 获取Post数据
     * @param null $param
     * @param null $defaultValue
     * @return array|mixed
     */
    public function post($param = null, $defaultValue = null)
    {
        return \Yii::$app->request->post($param, $defaultValue);
    }

    /**
     * 获取table格式的列表数据
     * @param $query
     * @param string $dealFunction
     * @param int $isObj
     * @return mixed
     */
    public function getJqTableData($query, $dealFunction = '', $isObj = 0)
    {
        //获取处理列表数据
        $listQuery = clone $query;
//        echo $listQuery->createCommand()->getRawSql();exit;
        if (empty($isObj)) {
            $listQuery->asArray();
        }
        $list = $listQuery->offset(($this->page - 1) * $this->pageSize)
            ->limit($this->pageSize)
            ->orderBy("$this->sidx $this->sord")
            ->all();

        if (!empty($dealFunction)) {
            $list = $dealFunction($list);
        }

        return $list;
    }

}