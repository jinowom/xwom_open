<?php
namespace wodrow\yii2wtools\validators;


use yii\validators\Validator;

class Username extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $username = $model->$attribute;
        $this->addError($username, $attribute, $attribute . '格式错误');
        $r = "/^[a-zA-Z\x{4e00}-\x{9fa5}][a-zA-Z0-9\x{4e00}-\x{9fa5}]+$/";
        preg_match($r, $username, $arr);
        if (!$arr){
            $this->addError($username, $attribute, $attribute . '格式错误');
        }
    }
}