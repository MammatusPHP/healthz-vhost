<?php

declare(strict_types=1);

namespace Mammatus\Tests\Vhost\Healthz;

use Mammatus\Http\Server\WebSockets\Broadcaster;
use Mammatus\Vhost\Healthz\HealthBroadcast;
use Prophecy\Argument;
use React\EventLoop\Factory;
use Thruway\ClientSession;
use Thruway\Peer\Client;
use Thruway\Role\Publisher;
use Thruway\Transport\TransportInterface;
use WyriHaximus\AsyncTestUtilities\AsyncTestCase;

use function WyriHaximus\React\timedPromise;

final class HealthzBroadcastTest extends AsyncTestCase
{
    /**
     * @test
     */
    final public function forwardToMiddleware(): void
    {
        $client         = $this->prophesize(Client::class);
        $revealedClient = $client->reveal();

        $clientSession = new ClientSession(
            $this->prophesize(TransportInterface::class)->reveal(),
            $revealedClient,
        );

        $publisher = $this->prophesize(Publisher::class);
        $publisher->publish(
            $clientSession,
            Argument::type('string'),
            Argument::type('array'),
            null,
            null,
        )->shouldBeCalled();

        $client->getPublisher()->shouldBeCalled()->willReturn($publisher->reveal());

        $loop = Factory::create();

        $healthBroadcast = new HealthBroadcast($loop);

        $healthBroadcast->broadcast(new Broadcaster($clientSession));

        $this->await(timedPromise($loop, 15), $loop, 20);
    }
}
