<?php

namespace jinowom\Pay\Gateways\Wechat;

use Symfony\Component\HttpFoundation\RedirectResponse;
use jinowom\Pay\Events;
use jinowom\Pay\Exceptions\GatewayException;
use jinowom\Pay\Exceptions\InvalidArgumentException;
use jinowom\Pay\Exceptions\InvalidSignException;

class WapGateway extends Gateway
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
    public function pay($endpoint, array $payload): RedirectResponse
    {
        $payload['trade_type'] = $this->getTradeType();

        Events::dispatch(new Events\PayStarted('Wechat', 'Wap', $endpoint, $payload));

        $mweb_url = $this->preOrder($payload)->get('mweb_url');

        $url = is_null(Support::getInstance()->return_url) ? $mweb_url : $mweb_url.
                        '&redirect_url='.urlencode(Support::getInstance()->return_url);

        return new RedirectResponse($url);
    }

    /**
     * Get trade type config.
     *
     * @author jinowom <chareler@163.com>
     */
    protected function getTradeType(): string
    {
        return 'MWEB';
    }
}
