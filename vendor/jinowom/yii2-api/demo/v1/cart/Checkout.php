<?php 
namespace jinowom\demo\v1\cart;
 
use yii\helpers\Inflector;
use yii\validators\RequiredValidator;
use yii\validators\Validator;
use yii\base\DynamicModel;

class Checkout extends \jinowom\api\IApi{  
    public $apiDescription = "购物车checkout";    
 
    public $auth=true;

    function params(){
        return [ 
            'cart_ids'=>['type'=>'string','validate'=>'required','demo'=>'1,2','description'=>'购物车IDs,多个请用逗号分隔'],
        ];
    }
 
    /**
     * @return object total 总价
     * @return float total.allPayment 应付总价
     * @return float total.allPostfee 运费
     * @return float total.disCountfee 优惠价
     * @return array cartInfo 购物车
     * @return int cartInfo[].cartid 购物车id
     * @return int cartInfo[].item_id 产品的id
     * @return int cartInfo[].num 数量
     * @return string sign 数字签名，用于传入order.create
     */
    function handle($params){   
        return [
            'total'=>[
                'allPayment'=>100.55,
                'allPostfee'=>1,
                'disCountfee'=>2,
            ],
            'cartInfo'=>[
                [
                    'cart_id'=>1,
                    'item_id'=>1,
                    'num'=>1,
                ],
                [
                    'cart_id'=>2,
                    'item_id'=>3,
                    'num'=>3,
                ]
            ],
            'sign'=>'abcedfedg',
        ];
    } 
 
 
 
}
 