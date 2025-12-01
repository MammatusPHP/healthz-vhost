<?php

declare(strict_types=1);

namespace Mammatus\Vhost\Healthz;

use Mammatus\Http\Server\Attributes\HttpMethod;
use Mammatus\Http\Server\Attributes\Route;
use Mammatus\Http\Server\Attributes\Vhost;
use Psr\Http\Message\ResponseInterface;
use React\Http\Message\Response;

#[Vhost('healthz')]
#[Route(HttpMethod::GET, '/')]
final class IndexHandler
{
    public static function handle(): ResponseInterface
    {
        return new Response(Response::STATUS_OK, ['Content-Type' => 'text/plain'], 'We good!');
    }
}
