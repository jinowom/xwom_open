<?php
namespace api\controllers;

use Yii;
use backend\modules\xportal\models\XportalAppVersion;
use services\ErrorService;
use services\ResponseService;

/**
 * Site controller
 */
class AppVersionController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * 获取APP版本号
     * @return array
     * @author rjl
     */
    public function actionIndex()
    {
        $data = XportalAppVersion::find()->from(XportalAppVersion::tableName())
            ->select(['version_number', 'version_system', 'version_url', 'version_content'])
            ->where(['is_del' => 0, 'status' => 1])
            ->orderBy('version_id desc')
            ->asArray()->one();

        if (empty($data)) {
            return ResponseService::response(ErrorService::STATUS_SUCCESS, (object)[]);
        }
        //添加域名
        $data['version_url'] = getVar('FILEURL') . $data['version_url'];
        
        return ResponseService::response(ErrorService::STATUS_SUCCESS,$data);
    }


}
