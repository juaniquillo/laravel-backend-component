<?php

declare(strict_types=1);

namespace Feature\IndividualComponents\Form;

use Juaniquillo\BackendComponents\Components\Individual\DivComponent;
use Juaniquillo\BackendComponents\Deserializers\IndividualComponentDeserializer;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class IndividualDivTest extends TestCase
{
    #[Test]
    public function empty_individual_div()
    {
        $div = new DivComponent;

        $this->blade('{{ $div }}', [
            'div' => $div,
        ])
            ->assertSee('<div', false)
            ->assertSee('</div>', false);
    }

    #[Test]
    public function individual_div_accepts_content()
    {
        $div = (new DivComponent)
            ->setContent('This is the content content');

        $this->blade('{{ $div }}', [
            'div' => $div,
        ])
            ->assertSee('<div', false)
            ->assertSee('This is the content content')
            ->assertSee('</div>', false);
    }

    #[Test]
    public function individual_div_accepts_contents_array()
    {
        $div = (new DivComponent)
            ->setContents([
                ComponentFactory::make(ComponentEnum::SPAN)
                    ->setContent('Span'),
                ComponentFactory::make(ComponentEnum::BOLD)
                    ->setContent('Bold'),
            ]);

        $this->blade('{{ $div }}', [
            'div' => $div,
        ])
            ->assertSee('<div', false)
            ->assertSee('<span', false)
            ->assertSee('Span')
            ->assertSee('</span>', false)
            ->assertSee('<b', false)
            ->assertSee('Bold')
            ->assertSee('</b>', false)
            ->assertSee('</div>', false);
    }

    #[Test]
    public function individual_div_input_accepts_attributes()
    {
        $div = (new DivComponent)
            ->setAttribute('id', 'div_id');

        $this->blade('{{ $div }}', [
            'div' => $div,
        ])
            ->assertSee('id="div_id"', false);
    }

    #[Test]
    public function div_accepts_theme()
    {
        $theme = [
            'color' => 'success',
        ];

        $div = (new DivComponent)
            ->setThemes($theme);

        $this->blade('{{ $div }}', [
            'div' => $div,
        ])
            ->assertSee('class="'.processThemes($theme), false);

    }

    #[Test]
    public function a_div_component_can_be_recreated_from_an_array()
    {
        $component = DivComponent::make()
            ->setContents([
                'span_1' => ComponentFactory::make(ComponentEnum::SPAN)
                    ->setContent('inside a span'),
                'span_2' => ComponentFactory::make(ComponentEnum::SPAN)
                    ->setContent(
                        ComponentFactory::make(ComponentEnum::LINK)
                            ->setAttribute('href', 'https://google.com')
                            ->setContent('this is a link')
                            ->setTheme('action', 'success')
                    ),
            ])
            ->setAttribute('id', 'div_id')
            ->setTheme('display', 'block');

        $componentArray = $component->toArray();

        $recreatedComponent = IndividualComponentDeserializer::fromArray($componentArray);

        $this->assertEquals($componentArray, $recreatedComponent->toArray());
    }
}
