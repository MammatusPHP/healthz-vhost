<?php

declare(strict_types=1);

namespace Mammatus\Vhost\Healthz;

use Mammatus\Http\Server\CommandBus\Result;
use Mammatus\Http\Server\WebSockets\Result as WebSocketResult;
use Psr\Http\Message\ResponseInterface;
use React\Http\Message\Response;

use function Safe\json_encode;

use const WyriHaximus\Constants\HTTPStatusCodes\OK;

final class HealthResult implements Result
{
    private string $result;

    public function __construct(string $result)
    {
        $this->result = $result;
    }

    public function response(): ResponseInterface
    {
        return new Response(OK, ['Content-Type' => 'text/plain'], json_encode(['result' => $this->result]));
    }

    public function result(): WebSocketResult
    {
        return new WebSocketResult\Array_([
            'result' => $this->result,
        ]);
    }
}
