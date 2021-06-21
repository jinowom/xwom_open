<?php 
namespace jinowom\demo\v1\user;
 
use jinowom\api\exception\LogicException;
use yii\web\UploadedFile;

class Upload extends \jinowom\api\IApi{  
    public $apiDescription = "用户上传头像1，使用file验证";    
 
    public $auth=false;

    function params(){
        return [ 
            'image'=>['type'=>'file','validate'=>[  //  参数参见 \yii\validators\FileValidator
                'file'=>[
                    'extensions'=>'png',
                    'uploadRequired'=>'请上传一个图片',
                    'maxFiles'=>1, 
                ],
                'required',
                'safe',
            ],'demo'=>'','description'=>'在文件域中使用image'],   
        ]; 
    } 
    /** 
     * @return string url 返回图片的地址
     */
    function handle($params){ 
        // 验证规则
        // /$params['image'] 是 yii\web\UploadedFile 一个实例
        //$params['image']->saveAs();

        return [
            'url'=>'url',
            'image'=>$params['image']
        ];

    } 
    
    function returnJson(){
        return '
        {"code":200,"msg":"success","data":{"url":"url"}}';
    }
 
}
 