<?php

declare(strict_types=1);

namespace Mammatus\Http\Server\HealthCheck;

use Chimera\Input;

final class FetchHealtz
{
    public static function fromInput(Input $input): FetchHealtz
    {
        return new self();
    }
}