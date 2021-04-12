<?php
namespace common\models;
use yii\base\Model;
use yii\web\UploadedFile;
use Imagine\Image\ImageInterface;
use yii\imagine\Image;
use backend\modules\common\models\ConfigWatermark;
use services\OssService;
use Yii;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => ''],
        ];
    }

    /**
     * @var 文件上传
     * $dir string    文件存储路径
     * $isOss string  是否使用oss 1:使用; 2:不使用;
     */
    public function upload($dir, $isOss="1"){
        $isOss  = getVar('ISOSS');
        if ( !is_dir('upload/'.$dir)) {
            mkdir('upload/'.$dir, 0777, true);
            chmod('upload/'.$dir, 0777);
        }
        if($isOss == "1"){// 启用对象存储
            $fileName = $dir.'/'. md5($this->imageFile->baseName) . time() . '.' . $this->imageFile->extension;
            $res = OssService::upload($fileName,$this->imageFile->tempName);
            if(!empty($res) && $res['code']=='200' ){
                return 'upload/'.$fileName;
            }else{
                return false;
            }
        }else{// 不启用对象存储
            $fileName = 'upload/'.$dir.'/'. md5($this->imageFile->baseName) . time() . '.' . $this->imageFile->extension;
            if ($this->imageFile->saveAs($fileName)) {
                //生成水印
                // self::waterMarkDo($fileName);
                return $fileName;
            } else {
                return false;
            }
        }
    }
    
    /**
     * @var 文件删除
     * $fileName string    文件存储路径
     * $isOss string  是否使用oss 1:使用; 2:不使用;
     */
    public static function deleteFile($fileName, $isOss="1"){
        $isOss  = getVar('ISOSS');
        if($isOss == "1"){// 启用对象存储;
            OssService::delete(str_replace("upload/","",$fileName));
        }else{// 不启用对象存储
            if (file_exists(iconv('utf-8', "GBK",$fileName))) {
                unlink(iconv('utf-8', "GBK",$fileName));
            }
        }
    }

    //将某文件拷贝到指定目录下
    public static function copyFile($filename,$dir){
        if ( !is_dir($dir)) {
            mkdir($dir, 0777, true);
            chmod($dir, 0777);
        }
        if(copy($filename,$dir.'/'.date('YmdHis',time()).'.jpg')){
            return $dir.'/'.date('YmdHis',time()).'.jpg';
        }
        return false;
    }

    //图片加图片水印
    public static function waterMarkDo($url){
        $selfUrl ='./'.$url; //源图
        $model = ConfigWatermark::find()->select('watermark_url,x,y')->andWhere(['is_del' =>0,'status'=>1,'type'=>1])->asArray()->one();
        if(!empty($model)){
            $tu = './'.$model['watermark_url'];
            $processPath ='./'.$url;//保存后的图片
            Image::watermark($selfUrl, $tu, [$model['x'],$model['y']])->save($processPath, ['quality'=> 60]);   
        }
    }

    //图片加文字水印
    public static function textDo($url){
        $fontFile = './lib/layui/font/msyh.ttf';
        $selfUrl ='./'.$url; //源图
        $tu = '哈哈哈 hello word';
        $processPath ='./'.$url;//保存后的图片
        Image::text($selfUrl, $tu, $fontFile, [10,10], ['color' => 'fff', 'size' => 35])->save($processPath);
    }

    //缩略图（压缩）
    public static function thumb($url,$newsType = 0){
        $thumbUrl = \Yii::$app->controller->module->id.'/'.\Yii::$app->controller->id.'/image/'.date("Ymd",time());
        $fileName = 'uploads/'.$thumbUrl.'/'. md5(time()) . time() . '.' .substr(strrchr($url, '.'), 1);
        if ( !is_dir('uploads/'.$thumbUrl)) {
            mkdir('uploads/'.$thumbUrl, 0777, true);
            chmod('uploads/'.$thumbUrl, 0777);
        }
        if($newsType == 3){
            \yii\imagine\Image::thumbnail($url, 64, 36,ImageInterface::THUMBNAIL_OUTBOUND)
            ->save($fileName);
        }else{
            \yii\imagine\Image::thumbnail($url, 96, 72,ImageInterface::THUMBNAIL_OUTBOUND)
            ->save($fileName);
        }

        return $fileName;
    }

    //测试
    public function test(){
        $url = 'upload/default/category-manage/2020-12-29/1609207040d6c5b252f07d89a228fe435a8f0ae2d8.jpg';
        UploadForm::waterMarkDo($url);
        die;
    }
}
?>