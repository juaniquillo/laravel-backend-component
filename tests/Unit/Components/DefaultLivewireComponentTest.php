<?php

declare(strict_types=1);

namespace Unit\Components;

use Juaniquillo\BackendComponents\Components\DefaultLivewireComponent;
use Juaniquillo\BackendComponents\Contracts\LivewireComponent;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DefaultLivewireComponentTest extends TestCase
{
    #[Test]
    public function it_can_render_a_livewire_component()
    {
        $component = DefaultLivewireComponent::make(
            // Livewire component class
            // cannot test since Livewire is not installed
            name: LivewireComponent::class
        );

        $component
            // not required for the specific livewire component
            // ->setLivewire(livewire: true)
            ->setLivewireKey(livewireKey: 'livewire-key')
            ->setLivewireParams(livewireParams: [
                'first_param' => 'First',
            ]);

        $this->assertEquals(
            expected: '@livewire($name, $params, key($key))',
            actual: $component->toHtml(),
        );
    }
}
