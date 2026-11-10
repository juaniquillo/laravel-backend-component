<?php

declare(strict_types=1);

namespace Tests\Feature\Components;

use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class DivTest extends TestCase
{
    #[Test]
    public function empty_div()
    {
        $div = ComponentFactory::make(ComponentEnum::DIV);

        $this->blade('{{ $div }}', [
            'div' => $div,
        ])
            ->assertSee('<div', false)
            ->assertSee('</div>', false);
    }

    #[Test]
    public function div_accepts_content()
    {
        $div = ComponentFactory::make(ComponentEnum::DIV)
            ->setContent(
                ComponentFactory::make(ComponentEnum::PARAGRAPH)
                    ->setContent('This is the content content')
            );

        $this->blade('{{ $div }}', [
            'div' => $div,
        ])
            ->assertSee('<div', false)
            ->assertSee('This is the content content')
            ->assertSee('</div>', false);
    }

    #[Test]
    public function div_accepts_contents_array()
    {
        $div = ComponentFactory::make(ComponentEnum::DIV)
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
    public function div_accepts_attributes()
    {
        $div = ComponentFactory::make(ComponentEnum::DIV)
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

        $div = ComponentFactory::make(ComponentEnum::DIV)
            ->setThemes($theme);

        $this->blade('{{ $div }}', [
            'div' => $div,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
