<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Themes;

use Juaniquillo\BaseBackendComponent\Concerns\IsThemeManager;
use Juaniquillo\BaseBackendComponent\Contracts\ThemeManager;

final class LocalThemeManager implements ThemeManager
{
    use IsThemeManager;

    public function __construct()
    {
        $path = \base_path('resources/views/_themes/tailwind/');

        $this->setDefaultPath($path);
        $this->setThemeExtension('.blade.php');

    }
}
