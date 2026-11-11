<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Factories;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;
use Juaniquillo\BackendComponents\Contracts\StaticFactory;
use Juaniquillo\BackendComponents\MainBackendComponent;
use Juaniquillo\BackendComponents\Themes\LocalThemeManager;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;

/**
 * Sets component's and theme's path
 * to the local view folder:
 *
 * component - resource/views/components
 * themes - resource/views/_themes
 */
class LocalComponentFactory implements StaticFactory
{
    public static function make(string|BackedEnum $name): Htmlable|CompoundComponent
    {

        $component = (new MainBackendComponent($name, new LocalThemeManager))
            ->useLocal();

        return $component;
    }
}
