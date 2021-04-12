<?php 
namespace jinowom\demo;


//rbac-demo
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\helpers\Json;
use yii\web\Response;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\web\ForbiddenHttpException;

/**
 * module 的存在
 */
class Module extends \jinowom\api\Module  {  
 
    /**
     * 验证是否需要登陆
     *
     * @param action $action
     * @return boolean  true 可以访问，false不能访问
     */
    public function checkAccess($action){     

        //cookie -demo 
        if($_COOKIE['isadmin']){
            return true;
        }else{
            return false;
        }

        //session-demo
        if($_SESSION['isadmin']){
            return true;
        }else{
            return false;
        }        


        //rbac-demo
        if(\Yii::$app->user->can($action->id)){
            return true;
        }else{
            return false;    
        }            
        return true; 
    }
}
