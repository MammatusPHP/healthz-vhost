<?php

declare(strict_types=1);

namespace Mammatus\Vhost\Healthz;

use Mammatus\Http\Server\WebSockets\RpcInput;

use function Safe\json_encode;

final class ReceiveHealthz
{
    private string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public static function fromInput(RpcInput $input): ReceiveHealthz
    {
        return new self(json_encode($input->data()));
    }

    public function message(): string
    {
        return $this->message;
    }
}
