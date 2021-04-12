<?php

namespace jinowom\Pay\Contracts;

use Symfony\Component\HttpFoundation\Response;
use jinowom\Supports\Collection;

interface GatewayInterface
{
    /**
     * Pay an order.
     *
     * @author jinowom <chareler@163.com>
     *
     * @param string $endpoint
     *
     * @return Collection|Response
     */
    public function pay($endpoint, array $payload);
}
