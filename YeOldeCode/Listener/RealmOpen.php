<?php

declare(strict_types=1);

namespace ReactiveApps\Command\HttpServer\Listener;

use Psr\Container\ContainerInterface;
use React\Promise\PromiseInterface;
use ReactiveApps\Command\HttpServer\Event\RealmOpen as RealmOpenEvent;
use WyriHaximus\Recoil\PromiseCoroutineWrapper;
use WyriHaximus\Recoil\QueueCallerInterface;

final class RealmOpen
{
    private ContainerInterface $container;

    private PromiseCoroutineWrapper $wrapper;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->wrapper   = PromiseCoroutineWrapper::createFromQueueCaller($container->get(QueueCallerInterface::class));
    }

    public function __invoke(RealmOpenEvent $event): void
    {
        foreach ($event->getRealm()->getRpcs() as $rpc) {
            $handler = $this->container->get($rpc->getCommand());
            $event->getSession()->register($rpc->getName(), function (array $args) use ($handler): PromiseInterface {
                return $this->wrapper->call(static function (callable $handler, array $args) {
                    return yield $handler(...$args);
                }, $handler, $args);
            });
        }
    }
}
