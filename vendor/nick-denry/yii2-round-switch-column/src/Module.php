<?php

namespace nickdenry\grid\toggle;

use Yii;
use yii\base\Module as BaseModule;
use yii\base\InvalidParamException;

/**
 * Provides global module configuration.
 * @author Nick Denry
 */
class Module extends BaseModule
{
    const SWITCH_KEY_ON = 'on';
    const SWITCH_KEY_OFF = 'off';
    /**
     * @var string model property name for switch "on" and "off" values
     */
    public $switchValues = 'switchValues';

    public function init()
    {
        parent::init();
    }
}
