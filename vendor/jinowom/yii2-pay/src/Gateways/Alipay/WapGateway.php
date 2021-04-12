<?php

namespace jinowom\Pay\Gateways\Alipay;

class WapGateway extends WebGateway
{
    /**
     * Get method config.
     *
     * @author jinowom <chareler@163.com>
     */
    protected function getMethod(): string
    {
        return 'alipay.trade.wap.pay';
    }

    /**
     * Get productCode config.
     *
     * @author jinowom <chareler@163.com>
     */
    protected function getProductCode(): string
    {
        return 'QUICK_WAP_WAY';
    }
}
