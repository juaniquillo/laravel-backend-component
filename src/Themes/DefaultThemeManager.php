<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Themes;

use Juaniquillo\BaseBackendComponent\Concerns\IsThemeManager;
use Juaniquillo\BaseBackendComponent\Contracts\ThemeManager;

final class DefaultThemeManager implements ThemeManager
{
    use IsThemeManager;

    public function __construct()
    {
        $this->setDefaultPath(__DIR__.'/../../resources/views/_themes/tailwind');
        $this->setThemeExtension('.blade.php');
    }
}
