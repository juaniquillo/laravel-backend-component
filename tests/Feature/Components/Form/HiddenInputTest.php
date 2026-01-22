<?php

declare(strict_types=1);

namespace Test\Feature\Components\Form;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class HiddenInputTest extends TestCase
{
    #[Test]
    public function simple_hidden_input()
    {
        $input = ComponentBuilder::make(ComponentEnum::HIDDEN_INPUT);

        $this->blade('{{ $input }}', [
            'input' => $input,
        ])
            ->assertSee('<input type="hidden"', false)
            ->assertSee('/>', false);
    }

    #[Test]
    public function hidden_input_does_not_accepts_content()
    {
        $input = ComponentBuilder::make(ComponentEnum::HIDDEN_INPUT)
            ->setContent(
                ComponentBuilder::make(ComponentEnum::SPAN)
                    ->setContent('This is a span')
            );

        $this->blade('{{ $input }}', [
            'input' => $input,
        ])
            ->assertDontSee('<span', false)
            ->assertDontSee('This is a span')
            ->assertDontSee('</span>', false);
    }

    #[Test]
    public function hidden_input_accepts_attributes()
    {
        $form = ComponentBuilder::make(ComponentEnum::HIDDEN_INPUT)
            ->setAttribute('id', 'input_id')
            ->setAttribute('value', 'Input');

        $this->blade('{{ $form }}', [
            'form' => $form,
        ])
            ->assertSee('id="input_id"', false)
            ->assertSee('value="Input"', false);
    }

    #[Test]
    public function hidden_input_accepts_theme()
    {
        $theme = [
            'display' => 'inline-block',
        ];

        $input = ComponentBuilder::make(ComponentEnum::HIDDEN_INPUT)
            ->setThemes($theme);

        $this->blade('{{ $input }}', [
            'input' => $input,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
