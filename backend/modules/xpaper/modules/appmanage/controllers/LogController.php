<?php

namespace backend\modules\xpaper\modules\appmanage\controllers;
use backend\controllers\BaseController;
class LogController extends BaseController
//class LogController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
