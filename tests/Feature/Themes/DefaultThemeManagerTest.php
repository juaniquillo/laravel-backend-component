<?php

declare(strict_types=1);

namespace Feature\Themes;

use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class DefaultThemeManagerTest extends TestCase
{
    #[Test]
    public function default_theme_manager_helps_creating_themes()
    {
        $theme = [
            'display' => 'flex',
        ];

        $manager = new DefaultThemeManager;

        $this->assertEquals($manager->processThemes($theme), processThemes($theme));

    }

    #[Test]
    public function default_theme_manager_helps_creating_themes_when_an_array_of_themes_is_passed()
    {
        $theme = [
            'color' => 'default',
            'flex' => [
                'gap-sm',
                'wrap',
            ],
        ];

        $manager = new DefaultThemeManager;

        $this->assertEquals($manager->processThemes($theme), processThemes($theme));

    }
}
