<?php

declare(strict_types=1);

namespace Mammatus\Vhost\Healthz;

use Mammatus\Http\Server\Annotations\Vhost;
use Mammatus\Http\Server\Annotations\WebSocket\Broadcast;
use Mammatus\Http\Server\WebSockets\Broadcast as BroadcastContract;
use Mammatus\Http\Server\WebSockets\Broadcaster;
use React\EventLoop\LoopInterface;

/**
 * @Vhost("healthz")
 * @Broadcast(realm="healthz")
 */
final class HealthBroadcast implements BroadcastContract
{
    private const INTERVAL = 13;
    private const TOPIC    = 'healthz';
    private const MESSAGE  = ['status' => 'healthy'];

    private LoopInterface $loop;

    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
    }

    public function broadcast(Broadcaster $broadcaster): void
    {
        $this->loop->addPeriodicTimer(self::INTERVAL, static function () use ($broadcaster): void {
            $broadcaster->broadcast(self::TOPIC, self::MESSAGE);
        });
    }
}
