<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Headers;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class H3Test extends TestCase
{
    #[Test]
    public function empty_h3_header()
    {
        $header = ComponentBuilder::make(ComponentEnum::H3);

        $this->blade('{{ $header }}', [
            'header' => $header,
        ])
            ->assertSee('<h3', false)
            ->assertSee('</h3>', false);
    }

    #[Test]
    public function h3_header_accepts_content()
    {
        $header = ComponentBuilder::make(ComponentEnum::H3)
            ->setContent('Nice h3 tag');

        $this->blade('{{ $header }}', [
            'header' => $header,
        ])
            ->assertSee('Nice h3 tag');
    }

    #[Test]
    public function h3_accepts_contents_array()
    {
        $header = ComponentBuilder::make(ComponentEnum::H3)
            ->setContents([
                ComponentBuilder::make(ComponentEnum::SPAN)
                    ->setContent('Span'),
                ComponentBuilder::make(ComponentEnum::BOLD)
                    ->setContent('Bold'),
            ]);

        $this->blade('{{ $header }}', [
            'header' => $header,
        ])
            ->assertSee('<h3', false)
            ->assertSee('<span', false)
            ->assertSee('Span')
            ->assertSee('</span>', false)
            ->assertSee('<b', false)
            ->assertSee('Bold')
            ->assertSee('</b>', false)
            ->assertSee('</h3>', false);
    }

    #[Test]
    public function h3_header_accepts_attributes()
    {
        $header = ComponentBuilder::make(ComponentEnum::H3)
            ->setAttribute('id', 'nice_header');

        $this->blade('{{ $header }}', [
            'header' => $header,
        ])
            ->assertSee('id="nice_header"', false);
    }

    #[Test]
    public function h3_header_accepts_theme()
    {
        $theme = [
            'color' => 'error',
        ];

        $header = ComponentBuilder::make(ComponentEnum::H3)
            ->setThemes($theme);

        $this->blade('{{ $header }}', [
            'header' => $header,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
