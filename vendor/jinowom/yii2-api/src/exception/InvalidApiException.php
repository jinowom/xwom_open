<?php
namespace jinowom\api\exception;
 
class InvalidApiException extends Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return '无效的请求';
    }
}
