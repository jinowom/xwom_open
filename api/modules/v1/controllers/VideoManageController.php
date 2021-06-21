<?php
namespace api\modules\v1\controllers;

use backend\modules\xportal\models\XportalVideoPositioning;
use api\models\XportalMember;
use services\ErrorService;
use services\ResponseService;


/**
 * Site controller
 */
class VideoManageController extends BaseController
{

    /**
     * 记录视频时间定位
     * @return array
     */
    public function actionPositioning()
    {
        //判断header中的token
        $headers = \Yii::$app->getRequest()->getHeaders();
        $token = $headers->get('access-token', '');
        if (empty($token)) {
            return ResponseService::response(ErrorService::NO_OPERATION_PERMISSIONS);
        }

        //通过token获取用户信息
        $getMember = XportalMember::findIdentityByAccessToken($token);
        if (empty($getMember)) {
            return ResponseService::response(ErrorService::MEMBER_EMPTY);
        }

        //判斷用戶的token是否过期
        if($getMember['token_exptime'] < time()){
            return ResponseService::response(ErrorService::TOKEN_OVERDUE);
        }

        $news_id = $this->post('news_id',null);
        if (empty($news_id)) {
            return ResponseService::response(ErrorService::CONTENT_EMPTY);
        }

        $file = $this->post('file',null);
        if (empty($file)) {
            return ResponseService::response(ErrorService::FILE_EMPTY);
        }

        $time = $this->post('time',null);
        if (empty($time)) {
            return ResponseService::response(ErrorService::TIME_EMPTY);
        }

        $status = $this->post('status',null);//状态[2:禁用;1:启用]

        $videoPositioning = XportalVideoPositioning::find()->from(XportalVideoPositioning::tableName())
            ->select(['id'])->Where(['news_id' => $news_id, 'member_id' => $getMember['member_id'], 'file' => $file, 'is_del' => 0, 'status' => 1])->one();
        if (!empty($videoPositioning)) {
            if(!empty($status)){
                $videoPositioning->status = $status;
            }
            $videoPositioning->time = $time;
            if ($videoPositioning->save()) {
                return ResponseService::response(ErrorService::STATUS_SUCCESS);
            }
            return ResponseService::response(ErrorService::STATUS_FAILED);
        }

        $model = new XportalVideoPositioning();

        $t                 = time();
        $model->member_id  = $getMember['member_id'];
        $model->news_id    = $news_id;
        $model->file       = $file;
        $model->time       = $time;
        $model->created_at = $t;
        $model->updated_at = $t;

        if($model->save()){
            return ResponseService::response(ErrorService::STATUS_SUCCESS);
        }
        return ResponseService::response(ErrorService::STATUS_FAILED);
    }

    /**
     * 获取视频时间定位
     * @return array
     */
    public function actionGetPositioning()
    {
        //判断header中的token
        $headers = \Yii::$app->getRequest()->getHeaders();
        $token   = $headers->get('access-token', '');
        if (empty($token)) {
            return ResponseService::response(ErrorService::NO_OPERATION_PERMISSIONS);
        }

        //通过token获取用户信息
        $getMember = XportalMember::findIdentityByAccessToken($token);
        if (empty($getMember)) {
            return ResponseService::response(ErrorService::MEMBER_EMPTY);
        }

        //判斷用戶的token是否过期
        if ($getMember['token_exptime'] < time()) {
            return ResponseService::response(ErrorService::TOKEN_OVERDUE);
        }

        $news_id = $this->get('news_id', null);
        if (empty($news_id)) {
            return ResponseService::response(ErrorService::CONTENT_EMPTY);
        }

        $file = $this->get('file', null);
        if (empty($file)) {
            return ResponseService::response(ErrorService::FILE_EMPTY);
        }

        $data = XportalVideoPositioning::find()->from(XportalVideoPositioning::tableName())
            ->select(['time'])->Where(['news_id' => $news_id, 'member_id' => $getMember['member_id'], 'file' => $file, 'is_del' => 0, 'status' => 1])->asArray()->one();
        if (!empty($data)) {
            return ResponseService::response(ErrorService::STATUS_SUCCESS, $data);
        }

        return ResponseService::response(ErrorService::STATUS_FAILED);
    }
    
}
