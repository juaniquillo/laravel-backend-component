<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Form;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class LegendTest extends TestCase
{
    #[Test]
    public function simple_legend()
    {
        $legend = ComponentBuilder::make(ComponentEnum::LEGEND);

        $this->blade('{{ $legend }}', [
            'legend' => $legend,
        ])
            ->assertSee('<legend', false);
    }

    #[Test]
    public function legend_accepts_content()
    {
        $legend = ComponentBuilder::make(ComponentEnum::LEGEND)
            ->setContent(
                ComponentBuilder::make(ComponentEnum::PARAGRAPH)
                    ->setContent('Span content')
            );

        $this->blade('{{ $legend }}', [
            'legend' => $legend,
        ])
            ->assertSee('<legend', false)
            ->assertSee('Span content')
            ->assertSee('</legend>', false);
    }

    #[Test]
    public function legend_accepts_contents_array()
    {
        $header = ComponentBuilder::make(ComponentEnum::LEGEND)
            ->setContents([
                'content 1 ',
                'content 2',
            ]);

        $this->blade('{{ $header }}', [
            'header' => $header,
        ])
            ->assertSee('<legend', false)
            ->assertSee('content 1 ')
            ->assertSee('content 2')
            ->assertSee('</legend>', false);
    }

    #[Test]
    public function legend_accepts_attributes()
    {
        $legend = ComponentBuilder::make(ComponentEnum::LEGEND)
            ->setAttribute('for', 'legend_for');

        $this->blade('{{ $legend }}', [
            'legend' => $legend,
        ])
            ->assertSee('for="legend_for"', false);
    }

    #[Test]
    public function legend_accepts_theme()
    {
        $theme = [
            'color' => 'default',
        ];

        $legend = ComponentBuilder::make(ComponentEnum::LEGEND)
            ->setThemes($theme);

        $this->blade('{{ $legend }}', [
            'legend' => $legend,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
