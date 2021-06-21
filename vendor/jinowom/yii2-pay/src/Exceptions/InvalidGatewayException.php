<?php

namespace jinowom\Pay\Exceptions;

class InvalidGatewayException extends Exception
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
        parent::__construct('INVALID_GATEWAY: '.$message, $raw, self::INVALID_GATEWAY);
    }
}
