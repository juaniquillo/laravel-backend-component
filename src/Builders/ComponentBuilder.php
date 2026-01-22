<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Builders;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Contracts\StaticBuilder;
use Juaniquillo\BackendComponents\MainBackendComponent;

class ComponentBuilder implements StaticBuilder
{
    public static function make(string|BackedEnum $name): Htmlable|CompoundComponent
    {
        return new MainBackendComponent($name);
    }
}
