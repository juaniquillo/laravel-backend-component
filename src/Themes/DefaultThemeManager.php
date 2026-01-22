<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Themes;

use Juaniquillo\BackendComponents\Concerns\IsThemeManager;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;

final class DefaultThemeManager implements ThemeManager
{
    use IsThemeManager;

    private ?string $defaultPath = null;

    public function __construct()
    {
        $this->setDefaultPath(__DIR__.'/../../resources/views/_themes/tailwind');
    }
}
