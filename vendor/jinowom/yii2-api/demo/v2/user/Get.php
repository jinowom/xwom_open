<?php 
namespace jinowom\demo\v2\user;
 
use yii\helpers\Inflector;
use yii\validators\RequiredValidator;
use yii\validators\Validator;
use yii\base\DynamicModel;

class Get extends \jinowom\demo\v1\user\Get{  

    public $apiDescription='用户详情，继承测试'; 
    /**
     * @return int uid 用户的id
     * @return string username 用户的名称
     * @return string mobile 用户的手机
     * @return string sex 性别
     * @return double balance 余额
     * @return object loginLog 最近登陆的日志
     * @return string loginLog[].ip 登陆的ip
     * @return string loginLog[].datex 登陆的时间
     */ 
    function handle($params){   
        return [
            'uid'=>123,
            'username'=>'frank',
            'mobile'=>'1599999956',
            'sex'=>'男',
            'balance'=>1090.55,
            'loginLog'=>[
                ['ip'=>'127.0.0.1','datex'=>'2019-02-20 18:20:10'],
                ['ip'=>'192.168.2.2','datex'=>'2019-02-18 15:10:00'],
            ],
            'x'=>date('Y-m-d H:i:s',1550555656)
        ]; 
    } 
  
 
 
}
 