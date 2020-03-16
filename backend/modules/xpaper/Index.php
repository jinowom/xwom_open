<?php

namespace backend\modules\xpaper;

/**
 * xpaper module definition class
 */
class Index extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\xpaper\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this-> modules =[
            'appmanage' => [
                'class' => 'backend\modules\xpaper\modules\appmanage\Index',
            ],
            'app' => [
                'class' => 'backend\modules\xpaper\modules\app\AppModules',
            ],
        ];     
        // custom initialization code goes here
    }
}
