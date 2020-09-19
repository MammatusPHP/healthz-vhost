<?php

declare(strict_types=1);

namespace Mammatus\Vhost\Healthz;

use Chimera\Input;

final class FetchHealthz
{
    public static function fromInput(Input $input): FetchHealthz
    {
        return new self();
    }
}
