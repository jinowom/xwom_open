<?php

namespace jinowom\Pay\Gateways\Wechat;

use Symfony\Component\HttpFoundation\Request;
use jinowom\Pay\Events;
use jinowom\Pay\Exceptions\GatewayException;
use jinowom\Pay\Exceptions\InvalidArgumentException;
use jinowom\Pay\Exceptions\InvalidSignException;
use jinowom\Pay\Gateways\Wechat;
use jinowom\Supports\Collection;

class RedpackGateway extends Gateway
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
        $payload['wxappid'] = $payload['appid'];

        if ('cli' !== php_sapi_name()) {
            $payload['client_ip'] = Request::createFromGlobals()->server->get('SERVER_ADDR');
        }

        if (Wechat::MODE_SERVICE === $this->mode) {
            $payload['msgappid'] = $payload['appid'];
        }

        unset($payload['appid'], $payload['trade_type'],
              $payload['notify_url'], $payload['spbill_create_ip']);

        $payload['sign'] = Support::generateSign($payload);

        Events::dispatch(new Events\PayStarted('Wechat', 'Redpack', $endpoint, $payload));

        return Support::requestApi(
            'mmpaymkttransfers/sendredpack',
            $payload,
            true
        );
    }

    /**
     * Find.
     *
     * @author jinowom <chareler@163.com>
     *
     * @param $billno
     */
    public function find($billno): array
    {
        return [
            'endpoint' => 'mmpaymkttransfers/gethbinfo',
            'order' => ['mch_billno' => $billno, 'bill_type' => 'MCHT'],
            'cert' => true,
        ];
    }

    /**
     * Get trade type config.
     *
     * @author jinowom <chareler@163.com>
     */
    protected function getTradeType(): string
    {
        return '';
    }
}
