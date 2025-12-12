<?php

declare(strict_types=1);

namespace Mammatus\Tests\Vhost\Healthz;

use Mammatus\Vhost\Healthz\IndexHandler;
use PHPUnit\Framework\Attributes\Test;
use React\Http\Message\Response;
use WyriHaximus\TestUtilities\TestCase;

final class IndexHandlerTest extends TestCase
{
    #[Test]
    final public function forwardToMiddleware(): void
    {
        $response = IndexHandler::handle();

        self::assertSame(Response::STATUS_TEMPORARY_REDIRECT, $response->getStatusCode());
        self::assertSame(['/index.html'], $response->getHeader('Location'));
        self::assertSame('#niethier', $response->getBody()->getContents());
    }
}
