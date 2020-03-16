<?php

namespace backend\modules\xpaper\controllers;

class ContentController extends \yii\web\Controller
{
    public function actionLeadinTxt()
    {
        return $this->render('leadin-txt');
    }

    public function actionLeadinXml()
    {
        return $this->render('leadin-xml');
    }

}
