<?php

declare(strict_types=1);

namespace Mammatus\Vhost\Healthz;

use Chimera\Input;

final class FetchIndex
{
    public static function fromInput(Input $input): FetchIndex
    {
        return new self();
    }
}
