<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Contracts;

use Illuminate\Contracts\Support\Htmlable;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

interface StaticBuilder
{
    public static function make(string|ComponentEnum $name): Htmlable|CompoundComponent;
}
