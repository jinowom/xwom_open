<?php

namespace backend\modules\xpaper\modules\app\controllers;
//use yii\web\Controller;
use backend\controllers\BaseController;

/**
 * Default controller for the `app` module
 */
//class DefaultController extends Controller
class DefaultController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        //查看是否调用成功。注释掉下面一行即可验证
//        echo 'hello resource modules, 当您看到这个消息，说明调用xpaper模块的app子模块成功</br>';
        return $this->render('index');
    }
}
