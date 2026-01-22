<?php

declare(strict_types=1);

namespace Unit\Components;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LivewireComponentTest extends TestCase
{
    #[Test]
    public function a_livewire_component_can_be_created()
    {
        $component = ComponentBuilder::make(LivewireComponent::class);

        $this->assertFalse($component->isLivewire());
        $this->assertNotEquals('livewire-key', $component->getLivewireKey());
        $this->assertArrayNotHasKey('first_param', $component->getLivewireParams());

        /**
         * The package does ot require livewire
         * It needs to be installed in order
         * to use it
         */
        $component
            ->setLivewire()
            ->setLivewireKey('livewire-key')
            ->setLivewireParams([
                'first_param' => 'First',
            ]);

        $this->assertTrue($component->isLivewire());
        $this->assertEquals('livewire-key', $component->getLivewireKey());
        $this->assertArrayHasKey('first_param', $component->getLivewireParams());
    }
}

final class LivewireComponent {}
