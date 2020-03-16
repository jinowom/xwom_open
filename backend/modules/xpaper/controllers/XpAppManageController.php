<?php

namespace backend\modules\xpaper\controllers;
//use backend\controllers\BaseController;
//class XpAppManageController extends BaseController
class XpAppManageController extends \backend\controllers\BaseController
{
    public function actionAnalysis()
    {
        return $this->render('analysis');
    }

    public function actionIndex()
    {
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
