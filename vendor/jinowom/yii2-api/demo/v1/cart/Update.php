<?php 
namespace jinowom\demo\v1\cart;
 
use yii\helpers\Inflector;
use yii\validators\RequiredValidator;
use yii\validators\Validator;
use yii\base\DynamicModel;

class Update extends \jinowom\api\IApi{  
    public $apiDescription = "更新一个购物车";    
 
    public $auth=true;

    function params(){
        return [ 
            'cart_id'=>['type'=>'int','validate'=>'required,number','demo'=>'123','description'=>'购物车ID,测试请传入1'],
            'item_id'=>['type'=>'int','validate'=>'required,number','demo'=>'123','description'=>'商品的id'],
            'num'=>['type'=>'int','validate'=>'required,number','demo'=>'123','description'=>'数量'],
        ];
    }
 
 
    function handle($params){   
        if($params['cart_id']!=1){
            throw new \Exception('购物车不存在');
        }
        return '';
    } 
 
  
 
}
 