<?php

declare(strict_types=1);

namespace Mammatus\Vhost\Healthz;

use Mammatus\Http\Server\Configuration\Vhost;
use Mammatus\Http\Server\Configuration\Webroot;
use Mammatus\Http\Server\Webroot\WebrootPath;

use function dirname;

use const WyriHaximus\Constants\Numeric\ONE;

final class HealthCheckVhost implements Vhost
{
    private const SERVER_NAME = 'healthz';
    private const LISTEN_PORT = 9666;

    public static function port(): int
    {
        return self::LISTEN_PORT;
    }

    public static function name(): string
    {
        return self::SERVER_NAME;
    }

    public static function webroot(): Webroot
    {
        return new WebrootPath(dirname(__DIR__, ONE) . '/webroot/');
    }

    public static function maxConcurrentRequests(): ?int
    {
        return null;
    }

    public function middleware(): iterable
    {
        yield from [];
    }
}
