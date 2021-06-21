<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
 
namespace jinowom\api;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\helpers\Json;
use yii\web\Response;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\web\ForbiddenHttpException;

/**
 * module 的存在
 */
class Module extends \yii\base\Module implements BootstrapInterface{ 
    /**
     * 定义的apis
     *
     * @var array
     */ 
    public $apiConfig=[];
    /**
     * 默认的版本号
     *
     * @var string
     */
    public $defaultVersion  ='v1';
    //响应处理类
    public $responseClass       ='jinowom\api\Response'; 
    //错误处理类
    public $errorHandlerClass   ='jinowom\api\ErrorHandler';       

    //用于检测后台会话的类,如果文档中心只能让登陆后的用户访问
    public $openAccess          =true; 

    //文档中心的 首页的模板
    public $overviewHtml        ='@jinowom/api/views/doc/index';

    //文档中心的标题
    public $docTitle="接口文档中心";

   
 
    /**
     * 认证的的方式 多个用逗号分隔 eg:Query,Basic
     *
     * @var string
     */
    public $authType='query,bearer,header';

    /**
     * token认证的集合，可使用的认证过滤器参阅 \yii\filters\auth
     *
     * @var array
     */
    public $builtInAuthTypes=[
        'query'=>['class'=>'yii\filters\auth\QueryParamAuth','tokenParam'=>'token'],
        'header'=>'yii\filters\auth\HttpHeaderAuth',
        'bearer'=>'yii\filters\auth\HttpBearerAuth', 
        //'basic'=>'yii\filters\auth\HttpBasicAuth',
    ];


    public $classZir=null;


    /**
     *
     * @inheritdoc
     */
    public $defaultRoute    = 'index';
    /**  
     * @inheritdoc
     */
    public $controllerNamespace = 'jinowom\api\controllers';   
    
   
 
    /**  
     * @inheritdoc
     */
    public function bootstrap($app){
         
        // delay attaching event handler to the view component after it is fully configured
        // $app->on(Application::EVENT_BEFORE_REQUEST, function () use ($app) {
        //     $app->getView()->on(View::EVENT_END_BODY, [$this, 'renderToolbar']);
        //     $app->getResponse()->on(Response::EVENT_AFTER_PREPARE, [$this, 'setDebugHeaders']);
        // });
          
        $app->getUrlManager()->addRules([
            [
                'class' => 'yii\web\UrlRule',
                'route' => $this->id,
                'pattern' => $this->id,
            ],
            [
                'class' => 'yii\web\UrlRule',
                'route' => $this->id . '/index/index',
                'pattern' => $this->id . '',
            ],
            [
                'class' => 'yii\web\UrlRule',
                'route' => $this->id . '/<controller>/<action>',
                'pattern' => $this->id . '/<controller:[\w\-]+>/<action:[\w\-]+>',
            ]
        ], true);
    }  

 
 

    private $_response;
    public function getResponse(){  
        if($this->_response) return $this->_response;
        $this->_response=Yii::createObject($this->responseClass);
        return $this->_response;
    }

 
 
    /**
     * 验证是否需要登陆
     *
     * @param action $action
     * @return boolean  true 可以访问，false不能访问
     */
    public function checkAccess($action){     
        return true;   
    } 
}
