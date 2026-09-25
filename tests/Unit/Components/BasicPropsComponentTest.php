<?php

declare(strict_types=1);

namespace Tests\Unit\Components;

use Juaniquillo\BackendComponents\Components\PropsComponent;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BasicPropsComponentTest extends TestCase
{
    #[Test]
    public function custom_props_can_be_passed_to_a_component()
    {
        $component = (new PropsComponent('props'))
            ->setAttribute('class', 'themed-div')
            ->setProp('customProp', new class
            {
                public string $key = 'prop-key';

                public string $value = 'this is the value';
            });

        $props = $component->getAttributeBag()->getProps();

        $this->assertCount(1, $props);

    }

    #[Test]
    public function custom_props_are_merged_with_the_rest_of_the_attributes()
    {
        $component = (new PropsComponent('props'))
            ->setAttribute('class', 'themed-div')
            ->setProp('customProp', new class
            {
                public string $key = 'prop-key';

                public string $value = 'this is the value';
            });

        $attrsAndProps = $component->getAttributeBag()->getAttributesAndProps();

        $this->assertCount(2, $attrsAndProps);

    }
}
