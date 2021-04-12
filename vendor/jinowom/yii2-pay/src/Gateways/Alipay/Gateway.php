<?php

namespace jinowom\Pay\Gateways\Alipay;

use jinowom\Pay\Contracts\GatewayInterface;
use jinowom\Pay\Exceptions\InvalidArgumentException;
use jinowom\Supports\Collection;

abstract class Gateway implements GatewayInterface
{
    /**
     * Mode.
     *
     * @var string
     */
    protected $mode;

    /**
     * Bootstrap.
     *
     * @author jinowom <chareler@163.com>
     *
     * @throws InvalidArgumentException
     */
    public function __construct()
    {
        $this->mode = Support::getInstance()->mode;
    }

    /**
     * Pay an order.
     *
     * @author jinowom <chareler@163.com>
     *
     * @param string $endpoint
     *
     * @return Collection
     */
    abstract public function pay($endpoint, array $payload);
}
