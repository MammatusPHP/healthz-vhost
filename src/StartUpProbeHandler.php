<?php

declare(strict_types=1);

namespace Mammatus\Vhost\Healthz;

use Mammatus\Http\Server\Attributes\HttpMethod;
use Mammatus\Http\Server\Attributes\Probe;
use Mammatus\Http\Server\Attributes\ProbeType;
use Mammatus\Http\Server\Attributes\Route;
use Mammatus\Http\Server\Attributes\Vhost;
use Psr\Http\Message\ResponseInterface;
use React\Http\Message\Response;

#[Vhost('healthz')]
#[Route(HttpMethod::GET, '/probe/startup')]
#[Probe(ProbeType::StartUp)]
final class StartUpProbeHandler
{
    public static function handle(): ResponseInterface
    {
        return new Response(Response::STATUS_OK, ['Content-Type' => 'application/json'], '{"result":"healthy"}');
    }
}
