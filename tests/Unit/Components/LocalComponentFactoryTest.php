<?php

declare(strict_types=1);

namespace Tests\Unit\Components;

use Juaniquillo\BackendComponents\Factories\LocalComponentFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\backendComponentNamespace;

class LocalComponentFactoryTest extends TestCase
{
    #[Test]
    public function a_local_component_can_be_created_using_a_factory()
    {
        $component = LocalComponentFactory::make('div');

        $this->assertStringStartsNotWith(backendComponentNamespace(), $component->getComponentPath());

    }
}
