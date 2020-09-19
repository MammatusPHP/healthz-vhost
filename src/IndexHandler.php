<?php

declare(strict_types=1);

namespace Mammatus\Vhost\Healthz;

use Chimera\Mapping\Routing\FetchEndpoint;
use Mammatus\Http\Server\Annotations\Vhost;
use Psr\Http\Message\ResponseInterface;
use React\Http\Message\Response;

use const WyriHaximus\Constants\HTTPStatusCodes\PERMANENT_REDIRECT;

/**
 * @Vhost("healthz")
 * @FetchEndpoint(app="healthz", path="/", query=FetchIndex::class, name="FetchIndex")
 */
final class IndexHandler
{
    public function handle(FetchIndex $request): ResponseInterface
    {
        return new Response(PERMANENT_REDIRECT, ['Location' => '/index.html'], 'Shoo!');
    }
}
