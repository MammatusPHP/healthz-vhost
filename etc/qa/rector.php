<?php

declare(strict_types=1);

use Rector\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector;
use WyriHaximus\TestUtilities\RectorConfig;

return RectorConfig::configure(dirname(__DIR__, 2))->withSkip([
    NarrowObjectReturnTypeRector::class,
]);
