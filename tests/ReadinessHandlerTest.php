<?php

declare(strict_types=1);

namespace Mammatus\Tests\Vhost\Healthz;

use Mammatus\Vhost\Healthz\ReadinessProbeHandler;
use PHPUnit\Framework\Attributes\Test;
use React\Http\Message\Response;
use WyriHaximus\TestUtilities\TestCase;

final class ReadinessHandlerTest extends TestCase
{
    #[Test]
    final public function forwardToMiddleware(): void
    {
        $json = '{"result":"healthy"}';

        $response = ReadinessProbeHandler::handle();
        self::assertSame(Response::STATUS_OK, $response->getStatusCode());
        self::assertSame(['application/json'], $response->getHeader('Content-Type'));
        self::assertSame($json, $response->getBody()->getContents());
    }
}
