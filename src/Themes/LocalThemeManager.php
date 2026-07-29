<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Themes;

use Juaniquillo\BackendComponents\Concerns\IsThemeManager;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;

final class LocalThemeManager implements ThemeManager
{
    use IsThemeManager;

    public function __construct()
    {
        $path = __DIR__.'/../../../../../resources/views/_themes/tailwind/';

        $this->setDefaultPath($path);

    }
}
