<?php

declare(strict_types=1);

namespace Mammatus\Tests\Vhost\Healthz;

use Mammatus\Http\Server\Webroot\WebrootPath;
use Mammatus\Vhost\Healthz\HealthCheckVhost;
use PHPUnit\Framework\Attributes\Test;
use WyriHaximus\TestUtilities\TestCase;

use function dirname;
use function iterator_to_array;

use const DIRECTORY_SEPARATOR;

final class HealthCheckVhostTest extends TestCase
{
    #[Test]
    final public function port(): void
    {
        self::assertSame(9666, HealthCheckVhost::port());
    }

    #[Test]
    final public function serverName(): void
    {
        self::assertSame('healthz', HealthCheckVhost::name());
    }

    #[Test]
    final public function webroot(): void
    {
        $expected = new WebrootPath(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public');

        self::assertEquals($expected, HealthCheckVhost::webroot());
    }

    #[Test]
    final public function maxConcurrentRequests(): void
    {
        self::assertNull(HealthCheckVhost::maxConcurrentRequests());
    }

    #[Test]
    final public function middleware(): void
    {
        $vhost = new HealthCheckVhost();

        self::assertSame([], iterator_to_array($vhost->middleware(), false));
    }
}
