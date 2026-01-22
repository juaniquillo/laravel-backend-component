<?php

declare(strict_types=1);

namespace Tests\Themes\Managers;

use Juaniquillo\BackendComponents\Concerns\IsThemeManager;

class TestThemeManager
{
    use IsThemeManager;

    private ?string $defaultPath = null;

    public function __construct() {}
}
