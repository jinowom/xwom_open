<?php

namespace jinowom\Pay\Tests;

use jinowom\Pay\Contracts\GatewayApplicationInterface;
use jinowom\Pay\Exceptions\InvalidGatewayException;
use jinowom\Pay\Gateways\Alipay;
use jinowom\Pay\Gateways\Wechat;
use jinowom\Pay\Pay;

class PayTest extends TestCase
{
    public function testAlipayGateway()
    {
        $alipay = Pay::alipay(['foo' => 'bar']);

        $this->assertInstanceOf(Alipay::class, $alipay);
        $this->assertInstanceOf(GatewayApplicationInterface::class, $alipay);
    }

    public function testWechatGateway()
    {
        $wechat = Pay::wechat(['foo' => 'bar']);

        $this->assertInstanceOf(Wechat::class, $wechat);
        $this->assertInstanceOf(GatewayApplicationInterface::class, $wechat);
    }

    public function testFooGateway()
    {
        $this->expectException(InvalidGatewayException::class);
        $this->expectExceptionMessage('INVALID_GATEWAY: Gateway [foo] Not Exists');

        Pay::foo([]);
    }
}
