<?php

namespace yigou\page\controllers\backend;

use Yii;
use yii\web\Controller;
use kiwi\Kiwi;

/**
 * Class HelperPageController
 * @package yigou\page\controllers\backend
 * @author Lujie.Zhou(lujie.zhou@jago-ag.cn)
 */
class PageCategoryController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
