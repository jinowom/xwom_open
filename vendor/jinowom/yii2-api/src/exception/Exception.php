<?php
namespace jinowom\api\exception;
 
class Exception extends \yii\base\Exception
{
 
    public function getName()
    {
        return '逻辑错误';
    } 

  
}
