<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Themes;

use Juaniquillo\BackendComponents\Concerns\IsThemeManager;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;

final class LocalThemeManager implements ThemeManager
{
    use IsThemeManager;

    private ?string $defaultPath = null;

    public function __construct()
    {
        $this->setDefaultPath(resource_path('views/_themes/tailwind/'));
    }
}
