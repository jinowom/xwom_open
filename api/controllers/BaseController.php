<?php
/**
 * Created by PhpStorm.
 * User: WANGWEIHUA
 * Date: 2019/8/4
 * Time: 11:14
 */

namespace api\controllers;

use yii\filters\ContentNegotiator;
use common\traits\BaseTraits;
use yii\web\Controller;
use yii\web\Response;
use Yii;

class BaseController extends Controller
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

}