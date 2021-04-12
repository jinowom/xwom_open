<?php
namespace jinowom\api;
use yii;
use yii\web\UnauthorizedHttpException;
use jinowom\api\exception\Exception;
use jinowom\api\Response;
use jinowom\api\exception\InvalidApiException;
use jinowom\api\controllers\IndexController;
 
class ErrorHandler extends \yii\web\ErrorHandler
{ 
    /**
     * 输出接管
     *
     * @param Exception $exception
     * @return void
     */
    protected function renderException($e){            
        $code=500;
        $res=IndexController::$moduleIns->getResponse();
        if($e instanceof UnauthorizedHttpException){
            $code=401;
        } 
        echo $res->renderException($e,$code);  
        exit;
    }
}
