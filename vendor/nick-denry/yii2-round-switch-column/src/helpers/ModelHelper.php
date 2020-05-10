<?php

namespace nickdenry\grid\toggle\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use nickdenry\grid\toggle\Module as RoundSwitchModule;

/**
 * Description of modelHelper
 *
 * @author Nick Denry
 */
class ModelHelper
{
    /**
     * @param ActiveRecord $model Yii model
     * @param string $type RoundSwitchModule::SWITCH_KEY_ON or RoundSwitchModule::SWITCH_KEY_OFF
     * @return mixed
     * @throws InvalidConfigException
     */
    public static function getToggleValue($model, $attribute, $type = RoundSwitchModule::SWITCH_KEY_ON)
    {
        $module = Yii::$app->getModule('roundSwitch');
        $switchValuesProperty = $module->switchValues;
        if ($model->hasProperty($switchValuesProperty)) {
            if (ArrayHelper::keyExists($attribute, $model->{$switchValuesProperty})) {
                return $model->{$module->switchValues}[$attribute][$type];
            }
            else
            {
                throw new InvalidConfigException('Attribute '.$attribute.' doesn\'t exist at '.$switchValuesProperty.' property array of '.get_class($model).' model');
            }
        }
        return $type == RoundSwitchModule::SWITCH_KEY_ON ? true : false;
    }

    /**
     * Check if model toggle attribute is equal to onValue or true (active)
     * @param ActiveRecord $model
     * @param string $attribute model attribute name
     * @return boolean
     */
    public static function isChecked($model, $attribute)
    {
        $onValue = self::getToggleValue($model, $attribute);
        return $model->{$attribute} == $onValue ? true : false;
    }
}
