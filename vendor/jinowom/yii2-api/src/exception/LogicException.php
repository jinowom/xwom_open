<?php
namespace jinowom\api\exception;
 
class LogicException extends Exception
{
 
    public function getName()
    {
        return '逻辑错误';
    }
}
