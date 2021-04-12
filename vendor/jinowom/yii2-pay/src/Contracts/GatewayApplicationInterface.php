<?php

namespace jinowom\Pay\Contracts;

use Symfony\Component\HttpFoundation\Response;
use jinowom\Supports\Collection;

interface GatewayApplicationInterface
{
    /**
     * To pay.
     *
     * @author jinowom <me@yansonga.cn>
     *
     * @param string $gateway
     * @param array  $params
     *
     * @return Collection|Response
     */
    public function pay($gateway, $params);

    /**
     * Query an order.
     *
     * @author jinowom <chareler@163.com>
     *
     * @param string|array $order
     *
     * @return Collection
     */
    public function find($order, string $type);

    /**
     * Refund an order.
     *
     * @author jinowom <chareler@163.com>
     *
     * @return Collection
     */
    public function refund(array $order);

    /**
     * Cancel an order.
     *
     * @author jinowom <chareler@163.com>
     *
     * @param string|array $order
     *
     * @return Collection
     */
    public function cancel($order);

    /**
     * Close an order.
     *
     * @author jinowom <chareler@163.com>
     *
     * @param string|array $order
     *
     * @return Collection
     */
    public function close($order);

    /**
     * Verify a request.
     *
     * @author jinowom <chareler@163.com>
     *
     * @param string|array|null $content
     *
     * @return Collection
     */
    public function verify($content, bool $refund);

    /**
     * Echo success to server.
     *
     * @author jinowom <chareler@163.com>
     *
     * @return Response
     */
    public function success();
}
