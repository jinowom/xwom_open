<?php
namespace wodrow\yii2wtools\validators;


use yii\validators\Validator;

class Mobile extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $mobile = $model->$attribute;
        if(strlen($mobile) == "11"){
            $r = "/^1[345678]{1}\d{9}$/";
            preg_match($r, $mobile, $arr);
            if (!$arr){
                $this->addError($model, $attribute, $attribute . '号码错误');
            }
        }else {
            $this->addError($model, $attribute, $attribute . '长度必须是11位');
        }
    }
}