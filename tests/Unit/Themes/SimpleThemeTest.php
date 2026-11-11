<?php

declare(strict_types=1);

namespace Tests\Unit\Themes;

use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;
use Juaniquillo\BackendComponents\Themes\LocalThemeManager;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SimpleThemeTest extends TestCase
{
    #[Test]
    public function a_package_theme_can_be_resolved_using_the_default_manager()
    {
        $manager = new DefaultThemeManager;

        $this->assertEquals('flex', $manager->processThemes(['display' => 'flex']));
    }

    #[Test]
    public function theme_managers_resolve_blade_files_from_the_package_and_app_paths()
    {
        $defaultManager = new DefaultThemeManager;
        $localManager = new LocalThemeManager;

        $this->assertStringEndsWith('resources/views/_themes/tailwind', $defaultManager->getDefaultPath());
        $this->assertSame('.blade.php', $defaultManager->getThemeExtension());

        $this->assertStringEndsWith('resources/views/_themes/tailwind/', $localManager->getDefaultPath());
        $this->assertSame('.blade.php', $localManager->getThemeExtension());
    }
}
