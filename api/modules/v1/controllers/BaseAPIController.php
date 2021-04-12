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

class BaseAPIController extends Controller
{
    //继承公共方法
    use BaseTraits;
    protected $except = []; // 排查一些方法不需要验证
    protected $pageSize = 2;//每页多少条
    protected $sidx = 'id';//排序的字段
    protected $sord = 'desc';//正序或倒序

    public function init()
    {
        parent::init();
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        //获取秘钥key
//        $sign = $this->get('sign');
//        $sign = 'admin3';
//        //查询秘钥数据库
//        $ApiService = new ApiService();
//        $isSign = $ApiService->getIsSgin($sign);
//        if (!$isSign){
//            throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获此操作的权限','403');
//        }
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

        /*if (!empty($list)){
            foreach ($list as $k => $v){
//                $list[$k]['collection'] = $k;//todo 此处要加收藏表，查询收藏表获得收藏数
            }
        }*/
        //获取总数
        $count = $query->count();

        if (!empty($list)){
            return $this->asJson(['code'  => 200,'msg'   => '成功','count' => (int)$count,'data'  => $list]);
        }
        return $this->asJson([ 'code'  => 400,'msg'   => '未查询到数据','count' => (int)$count,'data'  => []]);
    }

}