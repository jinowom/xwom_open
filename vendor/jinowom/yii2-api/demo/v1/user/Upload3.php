<?php 
namespace jinowom\demo\v1\user;
 
use jinowom\api\exception\LogicException;
use yii\web\UploadedFile;

class Upload3 extends \jinowom\api\IApi{  
    public $apiDescription = "用户上传头像3,多文件上传,自验证";    
 
    public $auth=false;

    function params(){
        return [ 
            'image'=>['type'=>'file','validate'=>'','demo'=>'','description'=>'在文件域中使用image[]'],        
        ]; 
    } 

    
    /** 
     * @return array urls[] url地址的数组     
     */
    function handle($params){ 
        $images=\yii\web\UploadedFile::getInstancesByName('image'); 
        //$image->saveAs();        

        return [
            'urls'=>[
                'url1',
                'url2',
                'url3',
            ],
            'image'=>$images
        ];

    } 
    
    function returnJson(){
        return '
        {"code":200,"msg":"success","data":{"urls":[]}}';
    }
 
}
 