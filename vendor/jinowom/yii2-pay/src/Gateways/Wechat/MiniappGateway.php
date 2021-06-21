<?php

namespace jinowom\Pay\Gateways\Wechat;

use jinowom\Pay\Exceptions\GatewayException;
use jinowom\Pay\Exceptions\InvalidArgumentException;
use jinowom\Pay\Exceptions\InvalidSignException;
use jinowom\Pay\Gateways\Wechat;
use jinowom\Supports\Collection;

class MiniappGateway extends MpGateway
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
        $payload['appid'] = Support::getInstance()->miniapp_id;

        if (Wechat::MODE_SERVICE === $this->mode) {
            $payload['sub_appid'] = Support::getInstance()->sub_miniapp_id;
            $this->payRequestUseSubAppId = true;
        }

        return parent::pay($endpoint, $payload);
    }
}
