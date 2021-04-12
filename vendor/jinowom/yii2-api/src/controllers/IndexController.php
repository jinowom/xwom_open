<?php
namespace jinowom\api\controllers;
use yii;  
use yii\filters\auth\QueryParamAuth;  
use yii\filters\auth\CompositeAuth;  
use yii\filters\RateLimiter;
use jinowom\api\ApiPackage;
use jinowom\api\IApi;
use jinowom\api\ErrorHandler;
use jinowom\api\Response;
use jinowom\api\exception\InvalidApiException;

/**
 * 负责调用的控制器
 */
class IndexController extends \yii\base\Controller{ 
	static public $moduleIns=null;
	private $clazz;

	/**
	 * 注册异常捕获类，用于接管抛出的异常
	 *
	 * @return void
	 */
	function init(){		  
		$this->layout=false;	
		IndexController::$moduleIns=$this->module;		
		$handler=Yii::createObject($this->module->errorHandlerClass);	 
		$handler->register();	
		$method=Yii::$app->request->get('method',null);		 
		$v=Yii::$app->request->get('v',$this->module->defaultVersion);	 
		if($method===null){
			throw new InvalidApiException('请传入方法');
		}
		$apis=$this->module->apiConfig;
		if(!isset($apis[$v])){
			throw new InvalidApiException('版本号不存在');
		} 
		if(!isset($apis[$v][$method])){
			throw new InvalidApiException('找不到API:'.$method);
		}
		$classz=$apis[$v][$method];
		if(is_string($classz)){
			$classz=['class'=>$classz];
		}
		if(!class_exists($classz['class'])){
			throw new InvalidApiException('找不到class:'.$classz['class']);
		}

		$this->clazz=Yii::createObject($classz);
		if(! $this->clazz instanceof IApi){
			throw new InvalidApiException('这不是一个IApi的实例');
		}
		 
	}
 
	/**
	 * invoke method
	 *
	 * @return void
	 */
	function actionIndex(){ 	 
		$return=$this->clazz->prepareCheck(); 	 
		if($this->clazz->beforeHandle($this->clazz->attributes)){
			$return=$this->clazz->handle($this->clazz->attributes); 			
		}else{
			throw new InvalidApiException('强制终止运行');
		}
		$return=$this->clazz->afterHandle($return);	 
		echo $this->module->getResponse()->renderSuccess($return); 	 
		exit;
	} 

	/**
	 * 验证的行为,包含  回话的验证，速率的验证
	 *
	 * @return void
	 */
	public function behaviors()	{
		$behaviors = parent::behaviors();
		if($this->clazz->auth==true){ 		
	 
			$as=explode(',',$this->module->authType);	
			$authMethods=[];
			foreach($as as $i) {		
				$v=$this->module->builtInAuthTypes[$i];
				if(empty($v)) throw new InvalidApiException('无效的authType参数:'.$i);
				$authMethods[]=$v;
			}	
			$behaviors['auth'] = [
				'class'=>CompositeAuth::class,
				'authMethods'=>$authMethods
			];			
		}	
		return $behaviors;
	}
 
}
