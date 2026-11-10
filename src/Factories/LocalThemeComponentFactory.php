<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Factories;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Contracts\StaticFactory;
use Juaniquillo\BackendComponents\MainBackendComponent;
use Juaniquillo\BackendComponents\Themes\LocalThemeManager;

/**
 * Sets theme's path
 * to the local view folder:
 *
 * themes - resource/views/_themes
 */
class LocalThemeComponentFactory implements StaticFactory
{
    public static function make(string|BackedEnum $name): Htmlable|CompoundComponent
    {
        $component = new MainBackendComponent($name, new LocalThemeManager);

        return $component;
    }
}
