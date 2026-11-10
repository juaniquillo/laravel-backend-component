<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Factories;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Contracts\StaticFactory;
use Juaniquillo\BackendComponents\MainBackendComponent;

class ComponentFactory implements StaticFactory
{
    public static function make(string|BackedEnum $name): Htmlable|CompoundComponent
    {
        return new MainBackendComponent($name);
    }
}
