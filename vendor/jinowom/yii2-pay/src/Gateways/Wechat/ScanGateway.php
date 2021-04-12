<?php

namespace jinowom\Pay\Gateways\Wechat;

use Symfony\Component\HttpFoundation\Request;
use jinowom\Pay\Events;
use jinowom\Pay\Exceptions\GatewayException;
use jinowom\Pay\Exceptions\InvalidArgumentException;
use jinowom\Pay\Exceptions\InvalidSignException;
use jinowom\Supports\Collection;

class ScanGateway extends Gateway
{
    /**
     * Pay an order.
     *
     * @author jinowom <chareler@163.com>
     *
     * @param string $endpoint
     *
     * @throws GatewayException
     * @throws InvalidArgumentException
     * @throws InvalidSignException
     */
    public function pay($endpoint, array $payload): Collection
    {
        $payload['spbill_create_ip'] = Request::createFromGlobals()->server->get('SERVER_ADDR');
        $payload['trade_type'] = $this->getTradeType();

        Events::dispatch(new Events\PayStarted('Wechat', 'Scan', $endpoint, $payload));

        return $this->preOrder($payload);
    }

    /**
     * Get trade type config.
     *
     * @author jinowom <chareler@163.com>
     */
    protected function getTradeType(): string
    {
        return 'NATIVE';
    }
}
