<?php 
namespace jinowom\demo;
use Yii;  
use yii\web\UnauthorizedHttpException;
/**
 * 这是一个用于演示的 Identity类 
 * 
 */
class Identity implements \yii\web\IdentityInterface{	 

    public static $tokenValidationKey='abcd';
    public $uid=123;
    public $name='frank'; 

    public static function findIdentity($id){	
        if($id!=123) {
            throw new \Exception('用户不存在');
        }        
        $id=new Identity();
        return $id;
    }

 
    /**
     * Undocumented function
     *
     * @param [type] $token
     * @param [type] $type
     * @return void
     */
    public static function findIdentityByAccessToken($token, $type = null){
        if(empty($token)){
            throw new \Exception('请传入token');
        }
        $as=list($uid,$time,$sign)=explode('-',$token);
        if(count($as)!=3){
            throw new \Exception('无效的token');
        }
        if((time()-$time)>3600*24*7){
            throw new \Exception('会话超时 请登陆',401);
        }
        $key=Identity::$tokenValidationKey; 
        if(md5($uid.$time.$key)!=$sign){
            throw new \Exception('无效登陆，请重新登陆',401);
        }
        return new Identity();  
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey(){
        $uid=$this->getId();        
        $key=Identity::$tokenValidationKey; 
        $time=time();
        $sign=md5($uid.$time.$key);       
        return "$uid-$time-$sign";
    }    
    /**
     * @inheritdoc,获取该认证实例表示的用户的ID。
     */
    public function getId(){
        return $this->uid;
    }
 




    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */  
    public static function findByUsername($username){ 
        return null;
    }


    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password){		
        return $this->password === $this->getEntryPassword($password,$this->password_salt);
    }
    
  

}
