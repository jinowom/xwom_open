<?php 
namespace jinowom\demo\v1\user;

use yii;
 

class Login extends \jinowom\api\IApi{  
    public $apiDescription = "手机登陆,颁发Token";    
    public $verbs='POST,GET'; 

    function params(){
        return [           
            'phone' => ['type'=>'string','validate'=>'required,number','description'=>'手机号','default'=>'','demo'=>'15996316989'], 
            
            'code' => ['validate'=>'required','description'=>'验证码','default'=>'','demo'=>'测试请输入：1234'], 
        ];
    }
 
    /**
     * @return string token 登陆成功的token：uid-time-sign
     * @return int uid 用户的id
     */
    function handle($params){  
        
  
        if($params['code']!='1234'){
            throw new \Exception('验证码不正确');
        }
     
        //测试一个 无存储的 identity 类
        $identity=new \jinowom\demo\Identity;

        if(Yii::$app->user->login($identity)){
            return [
                'token'=>$identity->getAuthKey(),
                'uid'=>$identity->getId(),
            ];
        }
        
        throw new \Exception('登陆失败'); 
    } 

    function returnJson(){
        return '
        {"code":200,"msg":"success","data":{"token":"123-1550515972-01672e6cbcb3833e18800e41284ea78f","uid":123}}';
    }
 
 
}
 