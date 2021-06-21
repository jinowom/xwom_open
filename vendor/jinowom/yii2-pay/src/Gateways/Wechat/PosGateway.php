<?php

namespace jinowom\Pay\Gateways\Wechat;

use jinowom\Pay\Events;
use jinowom\Pay\Exceptions\GatewayException;
use jinowom\Pay\Exceptions\InvalidArgumentException;
use jinowom\Pay\Exceptions\InvalidSignException;
use jinowom\Supports\Collection;

class PosGateway extends Gateway
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
        unset($payload['trade_type'], $payload['notify_url']);

        $payload['sign'] = Support::generateSign($payload);

        Events::dispatch(new Events\PayStarted('Wechat', 'Pos', $endpoint, $payload));

        return Support::requestApi('pay/micropay', $payload);
    }

    /**
     * Get trade type config.
     *
     * @author jinowom <chareler@163.com>
     */
    protected function getTradeType(): string
    {
        return 'MICROPAY';
    }
}
