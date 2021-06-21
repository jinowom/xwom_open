<?php

namespace jinowom\Pay\Gateways\Wechat;

use jinowom\Pay\Exceptions\InvalidArgumentException;

class RefundGateway extends Gateway
{
    /**
     * Find.
     *
     * @author jinowom <chareler@163.com>
     *
     * @param $order
     */
    public function find($order): array
    {
        return [
            'endpoint' => 'pay/refundquery',
            'order' => is_array($order) ? $order : ['out_trade_no' => $order],
            'cert' => false,
        ];
    }

    /**
     * Pay an order.
     *
     * @author jinowom <chareler@163.com>
     *
     * @param string $endpoint
     *
     * @throws InvalidArgumentException
     */
    public function pay($endpoint, array $payload)
    {
        throw new InvalidArgumentException('Not Support Refund In Pay');
    }

    /**
     * Get trade type config.
     *
     * @author jinowom <chareler@163.com>
     *
     * @throws InvalidArgumentException
     */
    protected function getTradeType()
    {
        throw new InvalidArgumentException('Not Support Refund In Pay');
    }
}
