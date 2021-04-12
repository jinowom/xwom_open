<?php
namespace api\controllers;

use Yii;
use backend\modules\xportal\models\XportalManagementVersion;
use services\ErrorService;
use services\ResponseService;

/**
 * Site controller
 */
class VersionController extends BaseController
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
     * 获取版本号
     * @return array
     * @author rjl
     */
    public function actionIndex()
    {
        $data = XportalManagementVersion::find()->from(XportalManagementVersion::tableName())
            ->select(['number'])
            ->where(['is_del' => 0, 'status' => 1])
            ->orderBy('id desc')
            ->asArray()->one();

        if (empty($data)) {
            return ResponseService::response(ErrorService::NOT_FOUND_VERSION);
        }

        return ResponseService::response(ErrorService::STATUS_SUCCESS,$data);
    }


}
