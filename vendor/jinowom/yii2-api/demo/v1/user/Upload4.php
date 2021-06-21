<?php 
namespace jinowom\demo\v1\user;
 
use jinowom\api\exception\LogicException;
use yii\web\UploadedFile;

class Upload4 extends \jinowom\api\IApi{  
    public $apiDescription = "用户上传头像4,base64上传";    
 
    public $auth=false;

    function params(){
        return [ 
            'image'=>['type'=>'string','demo'=>'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAMAAAAoyzS7AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAAZQTFRFF4MJAAAAMPoOJAAAAAxJREFUeNpiYAAIMAAAAgABT21Z4QAAAABJRU5ErkJggg==','description'=>'base64的字符'],        
            'format'=>['type'=>'string','demo'=>'json','demo'=>'json：输出，image：直接输出。默认json']
        ]; 
    } 

    
    /** 
     * @return string url 图片地址
     * 
     * image-test
     *  data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAMAAAAoyzS7AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAAZQTFRFF4MJAAAAMPoOJAAAAAxJREFUeNpiYAAIMAAAAgABT21Z4QAAAABJRU5ErkJggg==
     *
     */
    function handle($params){ 
        
        $params['format']=$params['format']?:'json'; 
        $str=explode(',',$params['image']);
        $image=base64_decode($str[1]);

        if($params['format']=='image'){
            header('Content-Type: image/png');  
            echo $image;        
            die;
        }

        //或者保存你的文件
        //file_put_contents($path, $image);
        return [
            'url'=>'path',
            'image'=>$str
        ];

    } 
    
    function returnJson(){
        return '
        {"code":200,"msg":"success","data":{"url":"url"}}';
    }
 
}
 