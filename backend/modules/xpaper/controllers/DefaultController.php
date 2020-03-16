<?php

namespace backend\modules\xpaper\controllers;

use backend\controllers\BaseController;

/**
 * Default controller for the `xpaper` module
 */
class DefaultController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        //查看是否调用成功。注释掉下面一行即可验证
//        echo 'hello resource modules, 当您看到这个消息，说明调用子模块xpaper成功</br>';
        return $this->render('index');
    }
}
