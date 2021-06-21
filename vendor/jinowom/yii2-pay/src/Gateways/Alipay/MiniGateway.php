<?php

namespace jinowom\Pay\Gateways\Alipay;

use jinowom\Pay\Events;
use jinowom\Pay\Exceptions\GatewayException;
use jinowom\Pay\Exceptions\InvalidArgumentException;
use jinowom\Pay\Exceptions\InvalidConfigException;
use jinowom\Pay\Exceptions\InvalidSignException;
use jinowom\Pay\Gateways\Alipay;
use jinowom\Supports\Collection;

class MiniGateway extends Gateway
{
    /**
     * Pay an order.
     *
     * @author xiaozan <i@xiaozan.me>
     *
     * @param string $endpoint
     *
     * @throws GatewayException
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws InvalidSignException
     *
     * @see https://docs.alipay.com/mini/introduce/pay
     */
    public function pay($endpoint, array $payload): Collection
    {
        $biz_array = json_decode($payload['biz_content'], true);
        if (empty($biz_array['buyer_id'])) {
            throw new InvalidArgumentException('buyer_id required');
        }
        if ((Alipay::MODE_SERVICE === $this->mode) && (!empty(Support::getInstance()->pid))) {
            $biz_array['extend_params'] = is_array($biz_array['extend_params']) ? array_merge(['sys_service_provider_id' => Support::getInstance()->pid], $biz_array['extend_params']) : ['sys_service_provider_id' => Support::getInstance()->pid];
        }
        $payload['biz_content'] = json_encode($biz_array);
        $payload['method'] = 'alipay.trade.create';
        $payload['sign'] = Support::generateSign($payload);

        Events::dispatch(new Events\PayStarted('Alipay', 'Mini', $endpoint, $payload));

        return Support::requestApi($payload);
    }
}
