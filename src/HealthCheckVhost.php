<?php

declare(strict_types=1);

namespace Mammatus\Vhost\Healthz;

use Mammatus\Groups\Attributes\Group;
use Mammatus\Groups\Type;
use Mammatus\Http\Server\Configuration\Vhost;
use Mammatus\Http\Server\Webroot\NoWebroot;
use Psr\Http\Server\MiddlewareInterface;

#[Group(Type::Daemon, 'healthz')]
final class HealthCheckVhost implements Vhost
{
    private const string SERVER_NAME = 'healthz';
    private const int LISTEN_PORT    = 9666;

    public static function port(): int
    {
        return self::LISTEN_PORT;
    }

    public static function name(): string
    {
        return self::SERVER_NAME;
    }

    public static function webroot(): NoWebroot
    {
        return new NoWebroot();
    }

    public static function maxConcurrentRequests(): int|null
    {
        return null;
    }

    /** @return iterable<MiddlewareInterface> */
    public function middleware(): iterable
    {
        yield from [];
    }
}
