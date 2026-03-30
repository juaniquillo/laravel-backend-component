<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Themes;

use Juaniquillo\BackendComponents\Concerns\IsThemeManager;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;
use Juaniquillo\BackendComponents\Exceptions\IncorrectThemePathException;

final class LocalThemeManager implements ThemeManager
{
    use IsThemeManager;

    private ?string $defaultPath = null;

    public function __construct()
    {
        $rawPath = __DIR__.'/../../../../../resources/views/_themes/tailwind/';
        $path = realpath($rawPath);

        if (!$path) {
            throw new IncorrectThemePathException(`The theme path "{$rawPath}" does not exist`);
        }

        $this->setDefaultPath($path);
        
    }
}
