<?php
namespace jinowom\api;

use Yii;
use ArrayAccess;
use ArrayObject;
use ArrayIterator;
use ReflectionClass;
use IteratorAggregate;
use yii\helpers\Inflector;
use yii\validators\RequiredValidator;
use yii\validators\Validator;
use yii\base\DynamicModel;
use jinowom\api\exception\InvalidApiException;

abstract class IApi extends DynamicModel{
    /**
     * 版本号
     *
     * @var string
     */
    public $version='v1';
    /**
     * 接口的名称
     *
     * @var string
     */
    public $name="接口的名称";
    /**
     * 允许的动作
     *
     * @var array
     */
    public $verbs='POST,GET';
    /**
     * 是否需要token验证
     *
     * @var boolean
     */
   
    public $cache=false;
    public $auth=false;
    public $apiManagerClass='jinowom\ApiManager';
    public $userModelClass='';

    public $apiDescription=''; 



    private $_identity;

  
    /**
     * 预先 检查
     *
     * @return void
     */
    public function prepareCheck(){
        $gmethod=Yii::$app->request->getMethod();
        if(stripos($this->verbs,$gmethod)===false){
            throw new InvalidApiException('请求的动作不允许');
        }      
        $params=array_keys($this->params()?:[]);           
        parent::__construct($params);

        $allparams=array_merge(Yii::$app->request->bodyParams,Yii::$app->request->queryParams);
        
        $this->setAttributes($allparams);
        
        $validators = $this->createValidators();
        $this->validate();
        $error=$this->getFirstErrorString();
        if(!empty($error)) throw new InvalidApiException($error);
    }

    /**
     * 必须实现 
     *
     * @param [type] $params
     * @return void
     */
    abstract function handle($params);
    /**
     * 参数的定义
     *
     * @param [type] $params
     * @return void
     */
    abstract function params();

 
    
    /**
     * 返回示例
     *
     * @return void
     */
    public function returnJson(){
        return '返回示例(无)';
    }

 
    /**
     * 当接口auth是true的时候，user才会存在
     *
     * @param \yii\web\IdentityInterface $identity 用户的实例
     * @return void
     */
    public function setIdentity($identity){
        $this->_identity=$identity;
    }

    public function getIdentity(){
        return $this->_identity;
    }
    /**
     * 获取第一个错误
     *
     * @return void
     */
    private function getFirstErrorString(){ 
        return current($this->getFirstErrors());
    }
 
    /**
     * 重载生成验证器
     *
     * @return void
     */
    public function createValidators(){   
        // $rules=$this->rules() ;
        // if(!empty($rules)) return parent::createValidators();
        $params=$this->params();
        $validators=new \ArrayObject; 
        foreach($params as $field=>$param){
            $validates=[];
            $validate=$param['validate'];
            if(is_array($validate)){
                $validates[]=$validate;
            }elseif(empty($validate)){
                $validates[]='safe';
            }elseif(stripos($validate,'_')!==false){
                $validates[]=$validate;
            }elseif(is_string($validate)){
                $vs=explode(',',$validate);
                foreach($vs as $i){
                    if(stripos($i,'in:')!==false){
                        $i=str_replace("in:","",$i);
                        $validates[]=[
                            'validate'=>'in',
                            'params'=>['range'=>explode('|',$i)]
                        ];  
                        continue;
                    }  
                    $validates[]=$i;                    
                }
            }        

            
            foreach($validates as $params){
                if(is_array($params)){                     
                    $validator = Validator::createValidator($params['validate'], $this, (array)$field,$params['params']);
                }else{
                    $validator = Validator::createValidator($params, $this, (array)$field,[]);
                }  
                $validator->skipOnEmpty=false;
                $validator->skipOnError=false;
                if($param['message'])
                    $validator->message=$param['message']; 
                $validators->append($validator); 
            }
        }       
        return $validators;
    }     

    /**
     * 反射 接口的注释
     *
     * @return void
     */
    function ref(){
        $method = new \ReflectionMethod ($this,'handle');
        $doc=$method->getDocComment();
        $list=explode("\n",$doc);
        foreach($list as $k=>$v){
            if(preg_match("/@return\s+(\S+)\s+(\S+)\s+(.+)/",$v,$mc)){
                $returns[]=[
                    'type'=>$mc[1],
                    'field'=>$mc[2],
                    'description'=>$mc[3],
                ];
            } 
            if(preg_match("/@mark (.+)/",$v,$mc)){
                $marks[]=$mc[1];
            } 
        }
        return [
            'returns'=>$returns,
            'marks'=>$marks,
        ];
    } 

    public function beforeHandle($attrs=[]){
        return true;
    }
    public function afterHandle($return=null){
        return $return;
    }
}
