<?php

namespace services;
require(__DIR__ . '/../vendor/aliyun-openapi-php-sdk-master/aliyun-php-sdk-core/Config.php');
use Yii;
use common\components\Service;
use common\models\config\ConfigOss;
//阿里云OSS执行
use OSS\OssClient;
use services\ErrorService;
use services\ResponseService;
//阿里云视频转码
use Mts\Request\V20140618 as Mts;
/**
 * Class MtsService
 * @package services
 * author:rjl
 */
class MtsService extends Service
{
    /**
     * 使用阿里云视频转码
     */
    public static function MtsManage($data){
        $getOss = ConfigOss::find()->from(ConfigOss::tableName())->select(['id','access_key_id', 'access_key_secret'])->Where(['is_del' => 0, 'status' => 1])->orderBy('order desc')->asArray()->one();
        
        if (empty($getOss)) {
            return ResponseService::response(ErrorService::CONTENT_EMPTY);
        }
        $access_key_id = $getOss['access_key_id'];
        $access_key_secret =$getOss['access_key_secret'];
        $mps_region_id = 'cn-beijing';
        $pipeline_id = 'ba607e0cd0594a8ab097da22927bb636';
        $template_id = 'S00000001-200010';
        $oss_location = 'oss-cn-beijing';
        $oss_bucket = 'hebeicentralbucket-beijing';
        $oss_input_object = str_replace("upload/","",$data['oss_input_object']);
        $type = $data['type'];
        $oss_output_object = substr($oss_input_object,0,strpos($oss_input_object, '.')).'/'.$type.'.mp4';
        # 创建DefaultAcsClient实例并初始化
        $clientProfile = \DefaultProfile::getProfile(
            $mps_region_id,                   # 您的 Region ID
            $access_key_id,                   # 您的 AccessKey ID
            $access_key_secret                # 您的 AccessKey Secret
        );
        $client = new \DefaultAcsClient($clientProfile);
        # 创建API请求并设置参数
        $request = new Mts\SubmitJobsRequest();
        $request->setAcceptFormat('JSON');
        # Input
        $input = array('Location' => $oss_location,
                    'Bucket' => $oss_bucket,
                    'Object' => urlencode($oss_input_object));
        $request->setInput(json_encode($input));
        # Output
        $output = array('OutputObject' => urlencode($oss_output_object));
        # Ouput->Container
        $output['Container'] = array('Format' => 'mp4');
        # Ouput->Video
        switch ($type) {
            case 'standard':
                   $output['Video'] = array('Codec' =>'H.264',
                    'Bitrate' => 800,
                    'Width' => 640,
                    'Fps' => 15);
                break;

            case 'high':
                   $output['Video'] = array('Codec' =>'H.264',
                    'Bitrate' => 2000,
                    'Width' => 720,
                    'Fps' => 20);
                break;

            case 'super':
                    $output['Video'] = array('Codec' =>'H.264',
                    'Bitrate' => 5000,
                    'Width' => 1280,
                    'Fps' => 25);
                break;
            default:
                return false;
                break;
        }
        $output['TemplateId'] = $template_id;
        $outputs = array($output);
        $request->setOUtputs(json_encode($outputs));
        $request->setOutputBucket($oss_bucket);
        $request->setOutputLocation($oss_location);
        $request->setPipelineId($pipeline_id);
        # 发起请求并处理返回
        try {
            $response = $client->getAcsResponse($request);
            // print 'RequestId is:' . $response->{'RequestId'} . "\n";;
            if ($response->{'JobResultList'}->{'JobResult'}[0]->{'Success'}) {
                // print 'JobId is:' .
                // $response->{'JobResultList'}->{'JobResult'}[0]->{'Job'}->{'JobId'} . "\n";
                return 'upload/'.$oss_output_object;
            } else {
                // print 'SubmitJobs Failed code:' .
                // $response->{'JobResultList'}->{'JobResult'}[0]->{'Code'} .
                // ' message:' .
                // $response->{'JobResultList'}->{'JobResult'}[0]->{'Message'} . "\n";
                return false;
            }
        } catch(\ServerException $e) {
            return false;
            // print 'Error: ' . $e->getErrorCode() . ' Message: ' . $e->getMessage() . "\n";
        } catch(\ClientException $e) {
            return false;
            // print 'Error: ' . $e->getErrorCode() . ' Message: ' . $e->getMessage() . "\n";
        }
    }

}