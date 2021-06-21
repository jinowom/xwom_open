<?php 
namespace jinowom\demo\v1\user;
 
use yii;
use yii\helpers\Inflector;
use yii\validators\RequiredValidator;
use yii\validators\Validator;
use yii\base\DynamicModel;

class Get extends \jinowom\api\IApi{  
    public $apiDescription = "获取用户详情";    
 
  

    function params(){
        return [ 
        ];
    }
 
    /**
     * @return int uid 用户的id
     * @return string username 用户的名称
     * @return string mobile 用户的手机
     * @return string sex 性别
     * @return double balance 余额
     * @return object loginLog 最近登陆的日志
     * @return string loginLog[].ip 登陆的ip
     * @return string loginLog[].date 登陆的时间
     */
    function handle($params){   

        $identity=Yii::$app->user->identity;
      
        return [
            'identity'=>$identity->getId(),
            'uid'=>123,
            'username'=>'frank',
            'mobile'=>'1599999956',
            'sex'=>'男2',
            'balance'=>1090.55,
            'loginLog'=>[
                ['ip'=>'127.0.0.1','date'=>'2019-02-20 18:20:10'],
                ['ip'=>'192.168.2.2','date'=>'2019-02-18 15:10:00'],
            ]
        ];
    } 
 
  
    function returnJson(){ 
        return '
        {"code":200,"msg":"success","data":{"uid":123,"username":"frank","mobile":"1599999956","sex":"男","balance":1090.55,"loginLog":[{"ip":"127.0.0.1","date":"2019-02-20 18:20:10"},{"ip":"192.168.2.2","date":"2019-02-18 15:10:00"}]}}';
    }
}
 