<?php 
namespace jinowom\demo\v1\cart;
 
use yii\helpers\Inflector;
use yii\validators\RequiredValidator;
use yii\validators\Validator;
use yii\base\DynamicModel;

class Get extends \jinowom\api\IApi{  
    public $apiDescription = "获取购物车的详情";    
 
    public $auth=true;

    function params(){
        return [ 
        ];
    }
 
    /**
     * @return float total 总金额 
     * @return float discount 优惠 
     * @return array list[] 购物车的列表 
     * @return int list[].cart_id 购物车id
     * @return int list[].item_id 产品的id
     * @return int list[].num 数量
     */
    function handle($params){   
        return [
            'total'=>55.8,
            'discount'=>1,
            'list'=>[
                [
                    'cart_id'=>1,
                    'item_id'=>2,
                    'num'=>1,  
                ],
                [
                    'cart_id'=>2,
                    'item_id'=>3,
                    'num'=>1,  
                ]              
            ]
        ];
    } 
 
    function returnJson(){
        return '{"code":200,"msg":"success","data":{"total":55.8,"discount":1,"list":[{"cart_id":1,"item_id":2,"num":1},{"cart_id":2,"item_id":3,"num":1}]}}';
    }
 
}
 