<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Inline;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class ButtonTest extends TestCase
{
    #[Test]
    public function simple_button()
    {
        $button = ComponentBuilder::make(ComponentEnum::BUTTON);

        $this->blade('{{ $button }}', [
            'button' => $button,
        ])
            ->assertSee('<button', false)
            ->assertSee('</button>', false);
    }

    #[Test]
    public function button_accepts_content()
    {
        $button = ComponentBuilder::make(ComponentEnum::BUTTON)
            ->setContent(
                ComponentBuilder::make(ComponentEnum::SPAN)
                    ->setContent('Span content')
            );

        $this->blade('{{ $button }}', [
            'button' => $button,
        ])
            ->assertSee('<button ', false)
            ->assertSee('<span', false)
            ->assertSee('Span content')
            ->assertSee('</span>', false)
            ->assertSee('</button>', false);
    }

    #[Test]
    public function button_accepts_contents_array()
    {
        $button = ComponentBuilder::make(ComponentEnum::BUTTON)
            ->setContents([
                ComponentBuilder::make(ComponentEnum::SPAN)
                    ->setContent('Span'),
                ComponentBuilder::make(ComponentEnum::BOLD)
                    ->setContent('Bold'),
            ]);

        $this->blade('{{ $button }}', [
            'button' => $button,
        ])
            ->assertSee('<button', false)
            ->assertSee('<span', false)
            ->assertSee('Span')
            ->assertSee('</span>', false)
            ->assertSee('<b', false)
            ->assertSee('Bold')
            ->assertSee('</b>', false)
            ->assertSee('</button>', false);
    }

    #[Test]
    public function button_accepts_attributes()
    {
        $button = ComponentBuilder::make(ComponentEnum::BUTTON)
            ->setContent('Nice button')
            ->setAttribute('type', 'submit');

        $this->blade('{{ $button }}', [
            'button' => $button,
        ])
            ->assertSee('<button', false)
            ->assertSee('type="submit"', false)
            ->assertSee('</button>', false);
    }

    #[Test]
    public function button_accepts_theme()
    {
        $theme = [
            'action' => 'default',
        ];

        $button = ComponentBuilder::make(ComponentEnum::BUTTON)
            ->setContent('Nice button')
            ->setThemes($theme);

        $this->blade('{{ $button }}', [
            'button' => $button,
        ])
            ->assertSee('<button', false)
            ->assertSee('class="'.processThemes($theme), false)
            ->assertSee('</button>', false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
