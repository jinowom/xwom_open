<?php
/**
 * @author  Womtech  email:chareler@163.com
 * @DateTime 2020-03-07 11:39 
 */

namespace backend\components;


use backend\behaviors\RbacBehavior;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\Event;
use yii\base\Module;
use yii\web\Controller;

class WorkflowBehaviorAttach extends Component implements BootstrapInterface
{
    protected $actions = ['*']; //需要验证的操作
    protected $except = [];     //需要验证的操作
    protected $mustlogin = [];  //必须要验证
    protected $verbs = [];      //控制访问的方式

    public function bootstrap($app)
    {
        Event::on(\jinostart\workflow\manager\Module::className(),Module::EVENT_BEFORE_ACTION,function($event){
            /**
             * @var $controller Controller
             */
            $controller = $event->action->controller;
            $controller->attachBehaviors([
                'access' => [
                    'class' => \yii\filters\AccessControl::className(),
                    'user' => 'admin',
                    'only' => $this->actions,
                    'except' => $this->except,
                    'rules' => [
                        [
                            'allow' => false,
                            'actions' => empty($this->mustlogin) ? [] : $this->mustlogin,
                            'roles' => ['?'],  //guest
                        ],
                        [
                            'allow' => true,
                            'actions' => empty($this->mustlogin) ? [] : $this->mustlogin,
                            'roles' => ['@'], //login
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => \yii\filters\VerbFilter::className(),
                    'actions' => $this->verbs
                ],
                'rbac' => RbacBehavior::className()
            ]);
        });
    }

}