<?php

declare(strict_types=1);

namespace Tests\Unit\Components;

use Juaniquillo\BackendComponents\Factories\LocalThemeComponentFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\backendComponentNamespace;

class LocalThemeComponentFactoryTest extends TestCase
{
    #[Test]
    public function a_local_theme_component_can_be_created_using_a_factory()
    {
        $component = LocalThemeComponentFactory::make('div');

        $this->assertStringStartsWith(backendComponentNamespace(), $component->getComponentPath());

    }
}
