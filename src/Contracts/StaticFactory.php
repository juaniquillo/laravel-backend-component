<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Contracts;

use Illuminate\Contracts\Support\Htmlable;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;

interface StaticFactory
{
    public static function make(string|ComponentEnum $name): Htmlable|CompoundComponent;
}
