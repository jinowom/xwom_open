<?php
/**
*Zip的一些压缩和解压的操作
*/
namespace common\helpers;
use Yii;
class Zip {
//解压压缩包并获取文件和路径
  public static function get_zip_originalsize($filename, $path) {
    //   print_r($filename);die;
        header("content-type:text/html;charset=utf8");
         //先判断待解压的文件是否存在
        if(file_exists($filename)){
           $zip = new \ZipArchive;

            if ($zip->open($filename) === TRUE){
                for ($i=0; $i < $zip->numFiles; $i++) {
                   $fileName[] =  $zip -> getNameIndex ( $i );
                }
                $zip->extractTo($path);//假设解压缩到在当前路径下images文件夹的子文件夹php

                $zip->close();//关闭处理的zip文件

                return $fileName;
            }
         }
    }
   //判断某文件是否为图片
   public static function isImage($postfix){
        $str = '.bmp,.jpg,.png,.tiff,.gif,.pcx,.tga,.exif,.fpx,.svg,.psd,.cdr,.pcd,.dxf,.ufo,.eps,.ai,.raw';
        return stristr($str,$postfix);
   } 
   //判断某文件是否为视频或音频
   public static function isVideo($postfix){
       $str = 'mp3,.wma,.avi,.rmvb,.flv,.mpg,.bai,.mpeg,.rm,.dat,.du,.wmv,.mov,.asf,.m1v,.m2v,.mpe,.qt,.vob,.ra,.rmj,.rms,.ram,.rmm,.ogm,.mkv,.avi_NEO_,.ifo,.mp4,.3gp,.rpm,.smi,.smil,.tp,.ts,.ifo,.mpv2,.mp2v,.tpr,.pss,.pva,.vg2,.drc,.ivf,.vp6,.vp7,.divx';
       return stristr($str,$postfix);
   }

   public static function deleteDir($path){
       //扫描一个目录内的所有目录和文件并返回数组
       $dirs = scandir($path);
       foreach ($dirs as $dir) {
           //排除目录中的当前目录(.)和上一级目录(..)
           if ($dir != '.' && $dir != '..') {
               //如果是目录则递归子目录，继续操作
               $sonDir = $path . '/' . $dir;
               if (is_dir($sonDir)) {
                   //递归删除
                   self::deleteDir($sonDir);
                   //目录内的子目录和文件删除后删除空目录
                   @rmdir($sonDir);
               } else {
                   //如果是文件直接删除
                   @unlink($sonDir);
               }
           }
       }
       $result = @rmdir($path) ? true : false;
       return true;
   }

       //处理压缩包
    public static function getDir($dir,$FileName){
        if (file_exists(iconv('utf-8', "GBK",$FileName))) {
            unlink(iconv('utf-8', "GBK",$FileName));
        }
        $path = 'protected/'.$dir.'/'.date("Ymdhis",time()).'/';
        $data = self::get_zip_originalsize('upload/'.$FileName, $path);
        foreach ($data as $key => $value) {
            if(stristr($value,'html')){
                $request['theme_dir'] = $path.$value;
            }
            $postfix = strstr($value,".");
            if($postfix){
                $img =  self::isImage($postfix);
                if($img){
                    $request['theme_image'] = $path.$value;
                }
            }
        }

        //删除压缩包
        if (file_exists(iconv('utf-8', "GBK",'upload/'.$FileName))) {
            unlink(iconv('utf-8', "GBK",'upload/'.$FileName));
        }
        return $request;
    }
}
 ?>