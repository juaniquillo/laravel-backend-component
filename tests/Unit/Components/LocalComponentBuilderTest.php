<?php

declare(strict_types=1);

namespace Tests\Unit\Components;

use Juaniquillo\BackendComponents\Builders\LocalComponentBuilder;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\backendComponentNamespace;

class LocalComponentBuilderTest extends TestCase
{
    #[Test]
    public function a_local_component_can_be_created_using_a_builder()
    {
        $component = LocalComponentBuilder::make('div');

        $this->assertStringStartsNotWith(backendComponentNamespace(), $component->getComponentPath());

    }
}
