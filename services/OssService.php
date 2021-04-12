<?php

namespace services;
use Yii;
use common\components\Service;
use common\models\config\ConfigOss;
//阿里云OSS执行
use OSS\OssClient;
use OSS\Core\OssException;
use services\ErrorService;
use services\ResponseService;
//阿里云视频转码
use Mts\Request\V20140618 as Mts;
/**
 * Class OssService
 * @package services
 * author:rjl
 */
class OssService extends Service
{
    /**
     * 使用阿里云oss上传文件
     * @param $object   保存到阿里云oss的文件名
     * @param $filepath 文件在本地的绝对路径
     * @return bool     上传是否成功
     */
    public static function upload($object, $filepath)
    {

        $getOss = ConfigOss::find()->from(ConfigOss::tableName())->select(['id','access_key_id', 'access_key_secret', 'bucket', 'endpoint', 'url', 'local_url', 'description'])->Where(['is_del' => 0, 'status' => 1])->orderBy('order desc')->asArray()->all();
        
        if (empty($getOss)) {
            return ResponseService::response(ErrorService::CONTENT_EMPTY);
        }
        
        foreach ($getOss as $v) {
            //获取阿里云oss的accessKeyId
            $accessKeyId     = $v['access_key_id'];
            //获取阿里云oss的accessKeySecret
            $accessKeySecret = $v['access_key_secret'];
            //获取阿里云oss的endPoint
            $endpoint        = $v['endpoint'];
            $bucket          = $v['bucket'];
            //实例化OssClient对象
            // $oss = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $oss = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            try {
                $oss->doesBucketExist($v['bucket']);
                break;
            } catch (OssException $e) {
                $oss = false;
                continue;
            };
        }
        
        //获取阿里云oss配置
        if(!$oss){
            return ResponseService::response(ErrorService::STATUS_FAILED);
        }
    
        //判断是否存在文件
        $doesObjectExist = $oss->doesObjectExist($bucket, $object);
        if($doesObjectExist){
            return ResponseService::response(ErrorService::EXISTING_OK);
        }
        
        $result = array();
        try {
            $getOssInfo = $oss->uploadFile($bucket, $object, $filepath);
            $result['url'] = $getOssInfo['info']['url'];
        } catch (OssException $e) {
            return ResponseService::response(ErrorService::STATUS_FAILED, $e->getMessage());
        };
        return ResponseService::response(ErrorService::STATUS_SUCCESS, $result);
    }

    /**
     * 删除指定文件
     * @param $object 被删除的文件名
     * @return bool   删除是否成功
     */
    public static function delete($object)
    {
        $getOss = ConfigOss::find()->from(ConfigOss::tableName())->select(['id','access_key_id', 'access_key_secret', 'bucket', 'endpoint', 'url', 'local_url', 'description'])->Where(['is_del' => 0, 'status' => 1])->orderBy('order desc')->asArray()->all();
        
        if (empty($getOss)) {
            return ResponseService::response(ErrorService::CONTENT_EMPTY);
        }

        foreach ($getOss as $v) {
            //获取阿里云oss的accessKeyId
            $accessKeyId     = $v['access_key_id'];
            //获取阿里云oss的accessKeySecret
            $accessKeySecret = $v['access_key_secret'];
            //获取阿里云oss的endPoint
            $endpoint        = $v['endpoint'];
            $bucket          = $v['bucket'];
            //实例化OssClient对象
            // $oss = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $oss = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            try {
                $oss->doesBucketExist($v['bucket']);
                break;
            } catch (OssException $e) {
                $oss = false;
                continue;
            };
        }

        //获取阿里云oss配置
        if(!$oss){
            return ResponseService::response(ErrorService::STATUS_FAILED);
        }

        //判断是否存在文件
        $doesObjectExist = $oss->doesObjectExist($bucket, $object);
        if(!$doesObjectExist){
            return ResponseService::response(ErrorService::CONTENT_EMPTY);
        }

        if ($oss->deleteObject($bucket, $object)){ //调用deleteObject方法把服务器文件上传到阿里云oss
            return ResponseService::response(ErrorService::STATUS_SUCCESS);
        }
        return ResponseService::response(ErrorService::SMS_SEND_FAILED);
    }


    /**
     * 使用阿里云oss分片上传文件
     * @param $object   保存到阿里云oss的文件名
     * @param $filepath 文件在本地的绝对路径
     * @return bool     上传是否成功
     */
    public static function multiuploadFile($object, $filepath)
    {
        $getOss = ConfigOss::find()->from(ConfigOss::tableName())->select(['id','access_key_id', 'access_key_secret', 'bucket', 'endpoint', 'url', 'local_url', 'description'])->Where(['is_del' => 0, 'status' => 1])->orderBy('order desc')->asArray()->all();
        
        if (empty($getOss)) {
            return ResponseService::response(ErrorService::CONTENT_EMPTY);
        }
        
        foreach ($getOss as $v) {
            //获取阿里云oss的accessKeyId
            $accessKeyId     = $v['access_key_id'];
            //获取阿里云oss的accessKeySecret
            $accessKeySecret = $v['access_key_secret'];
            //获取阿里云oss的endPoint
            $endpoint        = $v['endpoint'];
            $bucket          = $v['bucket'];
            //实例化OssClient对象
            // $oss = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $oss = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            try {
                $oss->doesBucketExist($v['bucket']);
                break;
            } catch (OssException $e) {
                $oss = false;
                continue;
            };
        }
        
        //获取阿里云oss配置
        if(!$oss){
            return ResponseService::response(ErrorService::STATUS_FAILED);
        }
    
        //判断是否存在文件
        $doesObjectExist = $oss->doesObjectExist($bucket, $object);
        if($doesObjectExist){
            return ResponseService::response(ErrorService::EXISTING_OK);
        }
        
        $result = array();
        try {
            $options = array(
                OssClient::OSS_CHECK_MD5 => true,
                OssClient::OSS_PART_SIZE => 1,
            );
            $getOssInfo = $oss->multiuploadFile($bucket, $object, $filepath, $options);
            $result['url'] = $getOssInfo['info']['url'];
        } catch (OssException $e) {
            return ResponseService::response(ErrorService::STATUS_FAILED, $e->getMessage());
        };
        return ResponseService::response(ErrorService::STATUS_SUCCESS, $result);
    }

}