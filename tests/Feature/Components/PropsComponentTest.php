<?php

declare(strict_types=1);

namespace Tests\Feature\Components;

use Juaniquillo\BackendComponents\Components\PropsComponent;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PropsComponentTest extends TestCase
{
    public function propClass(): object
    {
        return new class {
            public string $key = 'style';

            public string $value = 'text-align: center';

        };
    }

    #[Test]
    public function non_scalar_props_can_be_passed_if_the_props_interface_is_used(): void
    {
        $prop = $this->propClass();
        $component = (new PropsComponent('tests.props'));
        $component->setProp('customProp',$prop );

        $html = $this->blade('{{ $component }}', [
            'component' => $component,
        ])
            ->assertSee($prop->key, false)
            ->assertSee($prop->value, false);

    }
    
    #[Test]
    public function on_scalar_and_scalar_values_can_be_passed()
    {
        $prop = $this->propClass();
        $class = 'custom-class';

        $component = (new PropsComponent('tests.props'));
        $component->setProp('customProp',$prop )
            ->setAttribute('class', $class);

        $html = $this->blade('{{ $component }}', [
            'component' => $component,
        ])
            ->assertSee($prop->key, false)
            ->assertSee($prop->value, false)
            ->assertSee("class=\"$class\"", false);
    }
}
