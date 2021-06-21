<?php

namespace jinowom\filemanager\controllers;

use Yii;
use backend\controllers\BaseController;//use yii\web\Controller;//继承BaseController权限管理

//class DefaultController extends Controller
class DefaultController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSettings()
    {
        return $this->render('settings');
    }
}
