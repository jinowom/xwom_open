<?php
namespace api\controllers;

use yii\web\Controller;
use services\ErrorService;
use services\ResponseService;

/**
 * Site controller
 */
class SiteController extends Controller
{
    
    /**
     * {@inheritdoc}
     */
    public function actionError()
    {
        $error = \Yii::$app->errorHandler->exception;
        if(!empty($error->getCode()) && !empty($error->getMessage())){
            return ResponseService::response($error->getMessage());
        }
  
        return ResponseService::response(ErrorService::PAGE_ERROR);
    }
}
