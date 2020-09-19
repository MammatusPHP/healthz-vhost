<?php

declare(strict_types=1);

namespace Mammatus\Tests\Vhost\Healthz;

use Mammatus\TestUtilities\InputDummy;
use Mammatus\Vhost\Healthz\FetchHealthz;
use Mammatus\Vhost\Healthz\HealthzHandler;
use WyriHaximus\TestUtilities\TestCase;

use function Safe\json_encode;

use const WyriHaximus\Constants\HTTPStatusCodes\OK;

final class HealthzHandlerTest extends TestCase
{
    /**
     * @test
     */
    final public function forwardToMiddleware(): void
    {
        $json = '{"result":"healthy"}';

        $metricsHandler = new HealthzHandler();

        $result = $metricsHandler->handle(FetchHealthz::fromInput(new InputDummy()));

        $response = $result->response();
        self::assertSame(OK, $response->getStatusCode());
        self::assertSame(['text/plain'], $response->getHeader('Content-Type'));
        self::assertSame($json, (string) $response->getBody());

        self::assertSame($json, json_encode($result->result()));
    }
}
