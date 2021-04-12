<?php

namespace jinowom\Pay\Tests\Gateways;

use Symfony\Component\HttpFoundation\Response;
use jinowom\Pay\Pay;
use jinowom\Pay\Tests\TestCase;

class AlipayTest extends TestCase
{
    public function testSuccess()
    {
        $success = Pay::alipay([])->success();

        $this->assertInstanceOf(Response::class, $success);
        $this->assertEquals('success', $success->getContent());
    }
}
