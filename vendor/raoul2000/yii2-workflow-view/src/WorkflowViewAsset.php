<?php

namespace raoul2000\workflow\view;

use Yii;
use yii\web\AssetBundle;

class WorkflowViewAsset extends AssetBundle
{
    public $sourcePath = '@bower/vis/dist';
    /**
     * @see \yii\web\AssetBundle::init()
     */
    public function init()
    {
    	$this->js = [
    		'vis'.( YII_ENV_DEV ? '.js' : '.min.js' )
    	];
    	$this->css = [
    		'vis'.( YII_ENV_DEV ? '.css' : '.min.css' )
    	];    	
    	return parent::init();
    }    
}
