<?php

namespace jinowom\Pay;

use Exception;
use jinowom\Pay\Contracts\GatewayApplicationInterface;
use jinowom\Pay\Exceptions\InvalidGatewayException;
use jinowom\Pay\Gateways\Alipay;
use jinowom\Pay\Gateways\Wechat;
use jinowom\Pay\Listeners\KernelLogSubscriber;
use jinowom\Supports\Config;
use jinowom\Supports\Log;
use jinowom\Supports\Logger;
use jinowom\Supports\Str;

/**
 * @method static Alipay alipay(array $config) 支付宝
 * @method static Wechat wechat(array $config) 微信
 */
class Pay
{
    /**
     * Config.
     *
     * @var Config
     */
    protected $config;

    /**
     * Bootstrap.
     *
     * @author jinowom <chareler@163.com>
     *
     * @throws Exception
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);

        $this->registerLogService();
        $this->registerEventService();
    }

    /**
     * Magic static call.
     *
     * @author jinowom <chareler@163.com>
     *
     * @param string $method
     * @param array  $params
     *
     * @throws InvalidGatewayException
     * @throws Exception
     */
    public static function __callStatic($method, $params): GatewayApplicationInterface
    {
        $app = new self(...$params);

        return $app->create($method);
    }

    /**
     * Create a instance.
     *
     * @author jinowom <chareler@163.com>
     *
     * @param string $method
     *
     * @throws InvalidGatewayException
     */
    protected function create($method): GatewayApplicationInterface
    {
        $gateway = __NAMESPACE__.'\\Gateways\\'.Str::studly($method);

        if (class_exists($gateway)) {
            return self::make($gateway);
        }

        throw new InvalidGatewayException("Gateway [{$method}] Not Exists");
    }

    /**
     * Make a gateway.
     *
     * @author jinowom <me@yansonga.cn>
     *
     * @param string $gateway
     *
     * @throws InvalidGatewayException
     */
    protected function make($gateway): GatewayApplicationInterface
    {
        $app = new $gateway($this->config);

        if ($app instanceof GatewayApplicationInterface) {
            return $app;
        }

        throw new InvalidGatewayException("Gateway [{$gateway}] Must Be An Instance Of GatewayApplicationInterface");
    }

    /**
     * Register log service.
     *
     * @author jinowom <chareler@163.com>
     *
     * @throws Exception
     */
    protected function registerLogService()
    {
        $config = $this->config->get('log');
        $config['identify'] = 'jinowom.pay';

        $logger = new Logger();
        $logger->setConfig($config);

        Log::setInstance($logger);
    }

    /**
     * Register event service.
     *
     * @author jinowom <chareler@163.com>
     */
    protected function registerEventService()
    {
        Events::setDispatcher(Events::createDispatcher());

        Events::addSubscriber(new KernelLogSubscriber());
    }
}
