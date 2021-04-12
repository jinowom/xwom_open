<?php 
namespace jinowom\demo\v1\user;
 
use jinowom\api\exception\LogicException;
use yii\web\UploadedFile;

class Upload2 extends \jinowom\api\IApi{  
    public $apiDescription = "用户上传头像2,自己验证";    
 
    public $auth=false;

    function params(){
        return [ 
            'image'=>['type'=>'file','validate'=>'','demo'=>'','description'=>'在文件域中使用image'],   
     
        ]; 
    } 

    
    /** 
     * @return string url 返回图片的地址
     */
    function handle($params){ 
        $image=\yii\web\UploadedFile::getInstanceByName('image'); 
        //$image->saveAs();
        

        return [
            'url'=>'url',
            'image'=>$image
        ];

    } 
    
    function returnJson(){
        return '
        {"code":200,"msg":"success","data":{"url":"url"}}';
    }
 
}
 