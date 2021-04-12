<?php
namespace jinowom\api\controllers;
use yii;  
use yii\helpers\Url;  
use yii\web\Response as BResponse;  
use jinowom\api\ApiPackage;
use jinowom\api\IApi;
use jinowom\api\ErrorHandler;
use jinowom\api\Response;
use jinowom\api\Config;
use jinowom\api\exception\InvalidApiException;

/**
 * 负责调用的控制器
 */
class DocController extends \yii\web\Controller{ 
    public $title;

   
	/**
	 * 注册异常捕获类，用于接管抛出的异常
	 *
	 * @return void
	 */
	function init(){
	    $this->layout="@jinowom/api/views/layouts/main";
    }

    /**
     * 模板渲染指定路径
     *
     * @param [type] $view
     * @param array $params
     * @return void
     */
    function render($view,$params=[]){
        return parent::render('@jinowom/api/views/doc/'.$view,$params);
    }

	/**
	 * invoke method
	 *
	 * @return void
	 */
	function actionIndex(){                
        $this->title('首页');
        return parent::render($this->module->overviewHtml);
    }
 
    function actionGroup(){       
 
        $list=Config::getActiveGroupList();
        
        $g=Yii::$app->request->get('g');  

     
        return $this->title($list['gname'].' 相关接口')->render('group',[
            'list'=>$list,
            'g'=>$g,
            'gname'=>$list['gname'],
            'count'=>count($list['list']),
            'v'=>$this->getNowv(),
        ]);
    }


    function actionInfo($view='info'){
        $info=Config::getMethodInfo();      
        $method=Yii::$app->request->get('method');  
        $g=Yii::$app->request->get('g');  
 
 
        return $this->title($method.'接口的详情')->render($view,[            
            'class'=>$info['class'],
            'method'=>$method,
            'apiUrl'=>Yii::$app->request->hostInfo .Url::to(['index/index','v'=>$this->getNowv(),'method'=>$method]),
            'v'=>$this->getNowv(),
            'gname'=>$info['gname'],
            'g'=>$g,
            'verbs'=>$info['class']->verbs,
        ]);
    }


    function actionTest(){  
        return $this->title('测试：'.$method)->actionInfo('test'); 
    } 
    function actionSearch(){
        $key=Yii::$app->request->get('key');
  
        $all=Yii::$app->controller->module->apiConfig;
 
        $list=Config::searchKey($key);

        return $this->title("搜索 $key")->render('search',[
            'v'=>$this->getNowv(),
            'list'=>$list,
            'key'=>$key,
        ]);
    }

    /**
     * 执行测试
     *
     * @return void
     */
    function actionDotest(){
        $data=$_REQUEST;     
        if($data['data']['token']){
            \setcookie('test_token',$data['data']['token'],time()+3600*24*30,'/');
        }
        $start = \microtime(true);
        $curl = curl_init(true);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $reqUrl='';
        if(preg_match("/get/",strtolower($data['verbs']))){
            $url=http_build_query($data['data']);     
            $reqUrl=$data['apiurl'].'&'.$url;
            curl_setopt($curl, CURLOPT_URL,$reqUrl);
        }else{
            $reqUrl=$data['apiurl'];
            curl_setopt($curl, CURLOPT_URL,$data['apiurl']);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data['data']);  
        } 
        $data = curl_exec($curl);
        $end = \microtime(true);
        // 检查是否有错误发生
        if(curl_errno($curl)){
            $return='curl error:'.curl_error($curl);     
            $return=[
                'code'=>'500',
                'msg'=>'curl error:'.curl_error($curl),
            ];       
        }else{
            $return=$data;
        }
        curl_close($curl);        
        return $this->asJson([
            'return'=>$return,
            'returnJson'=>is_array($return)?$return:json_decode($return),
            'time'=>round($end-$start,4),
            'reqUrl'=>$reqUrl
        ]);
 
    }


    /**
     * 设置title
     *
     * @param [type] $title
     * @return void
     */
    public function title($title){
        $this->title=$title;
        return $this;
    }
    /**
     * 获取当前的 版本号
     *
     * @return void
     */
    private function getNowv(){
        return Yii::$app->request->get('v',$this->module->defaultVersion);
    }
 
  
    function beforeAction($action){  
        if($this->module->openAccess===false && !$this->module->checkAccess($action)){
            throw new InvalidApiException('对不起，您现在还没获此操作的权限');
        }      
        return true;
    }
}
