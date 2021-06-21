<?php

namespace jinowom\Pay\Exceptions;

class BusinessException extends GatewayException
{
    /**
     * Bootstrap.
     *
     * @author jinowom <me@yansonga.cn>
     *
     * @param string       $message
     * @param array|string $raw
     */
    public function __construct($message, $raw = [])
    {
        parent::__construct('ERROR_BUSINESS: '.$message, $raw, self::ERROR_BUSINESS);
    }
}