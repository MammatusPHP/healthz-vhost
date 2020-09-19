<?php

declare(strict_types=1);

namespace Mammatus\Tests\Vhost\Healthz;

use Mammatus\Http\Server\WebSockets\RpcInput;
use Mammatus\Vhost\Healthz\ClientHealthzHandler;
use Mammatus\Vhost\Healthz\ReceiveHealthz;
use Psr\Log\LoggerInterface;
use stdClass;
use WyriHaximus\TestUtilities\TestCase;

final class ClientHealthzHandlerTest extends TestCase
{
    /**
     * @test
     */
    final public function forwardToMiddleware(): void
    {
        $std      = new stdClass();
        $std->soa = 'nee';

        $logger = $this->prophesize(LoggerInterface::class);
        $logger->notice('A client resports to be healthy with the following message "{"soa":"nee"}", yay!')->shouldBeCalled();

        $metricsHandler = new ClientHealthzHandler($logger->reveal());

        $metricsHandler->handle(ReceiveHealthz::fromInput(new RpcInput($std)));
    }
}
