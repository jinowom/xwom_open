<?php 
namespace jinowom\demo\v1\user;
 
use yii\helpers\Inflector;
use yii\validators\RequiredValidator;
use yii\validators\Validator;
use yii\base\DynamicModel;

class Decode extends \jinowom\api\IApi{  
    public $apiDescription = "小程序解码encryptedData数据";    
 
  
    function params(){
        return [           
            'encryptedData' => ['type'=>'string','validate'=>'required','description'=>'wx.getuserInfo 返回的encryptedData','default'=>'','demo'=>'0L0ttqhdtzIgggggggggggggggggggggOg19NNw5T/7Q=='],
            'iv' => ['type'=>'string','validate'=>'required','description'=>'wx.getuserInfo 返回的iv','default'=>'','demo'=>'z5XdYgVMlugP8c4ztLx+7Q=='],
            'session_key' => ['type'=>'string','validate'=>'required','description'=>'user.xiaochengxu.token接口返回','default'=>'','demo'=>'L0ttqhdtzIOg19NNw5T/7Q=='],
 
        ];
    }
 
  
    function handle($params){       
        $aesKey=base64_decode($params['session_key']);
        $aesIV=base64_decode($params['iv']);
        $encryptedData=base64_decode($params['encryptedData']);        
        $result=openssl_decrypt( $encryptedData, "AES-128-CBC", $aesKey, 1, $aesIV);   
        if(empty($result)){
            throw new \Exception(('解码错误'));
        }
        $result=json_decode($result,1);   
        if(empty($result)){
            throw new \Exception(('解码JSON错误'));
        }
        return $result;
    }
 
 
 
}
 