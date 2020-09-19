<?php

declare(strict_types=1);

namespace Mammatus\Tests\Vhost\Healthz;

use Mammatus\TestUtilities\InputDummy;
use Mammatus\Vhost\Healthz\FetchIndex;
use Mammatus\Vhost\Healthz\IndexHandler;
use WyriHaximus\TestUtilities\TestCase;

use const WyriHaximus\Constants\HTTPStatusCodes\PERMANENT_REDIRECT;

final class IndexHandlerTest extends TestCase
{
    /**
     * @test
     */
    final public function forwardToMiddleware(): void
    {
        $metricsHandler = new IndexHandler();

        $response = $metricsHandler->handle(FetchIndex::fromInput(new InputDummy()));

        self::assertSame(PERMANENT_REDIRECT, $response->getStatusCode());
        self::assertSame(['/index.html'], $response->getHeader('Location'));
        self::assertSame('Shoo!', (string) $response->getBody());
    }
}
