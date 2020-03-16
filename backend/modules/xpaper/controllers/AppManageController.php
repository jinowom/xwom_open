<?php

namespace backend\modules\xpaper\controllers;
//use backend\controllers\BaseController;
//class AppManageController extends BaseController
class AppManageController extends \backend\controllers\BaseController
{
    public function actionAnalysis()
    {
        return $this->render('analysis');
    }

    public function actionIndex()
    {
//        //获取子模块
//        $appm = \YII::$app->getModule('app');
//        //调用子子模块
//        $appm->RunAction('default/index');
        return $this->render('index');
    }

    public function actionInstall()
    {
        return $this->render('install');
    }

    public function actionLog()
    {
        return $this->render('log');
    }

    public function actionPullmsg()
    {
        return $this->render('pullmsg');
    }

    public function actionPushmsg()
    {
        return $this->render('pushmsg');
    }

    public function actionUpdate()
    {
        return $this->render('update');
    }

}
