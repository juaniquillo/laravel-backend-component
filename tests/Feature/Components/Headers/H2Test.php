<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Headers;

use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class H2Test extends TestCase
{
    #[Test]
    public function empty_h2_header()
    {
        $header = ComponentFactory::make(ComponentEnum::H2);

        $this->blade('{{ $header }}', [
            'header' => $header,
        ])
            ->assertSee('<h2', false)
            ->assertSee('</h2>', false);
    }

    #[Test]
    public function h2_header_accepts_content()
    {
        $header = ComponentFactory::make(ComponentEnum::H2)
            ->setContent('Nice h2 tag');

        $this->blade('{{ $header }}', [
            'header' => $header,
        ])
            ->assertSee('Nice h2 tag');
    }

    #[Test]
    public function h2_accepts_contents_array()
    {
        $header = ComponentFactory::make(ComponentEnum::H2)
            ->setContents([
                ComponentFactory::make(ComponentEnum::SPAN)
                    ->setContent('Span'),
                ComponentFactory::make(ComponentEnum::BOLD)
                    ->setContent('Bold'),
            ]);

        $this->blade('{{ $header }}', [
            'header' => $header,
        ])
            ->assertSee('<h2', false)
            ->assertSee('<span', false)
            ->assertSee('Span')
            ->assertSee('</span>', false)
            ->assertSee('<b', false)
            ->assertSee('Bold')
            ->assertSee('</b>', false)
            ->assertSee('</h2>', false);
    }

    #[Test]
    public function h2_header_accepts_attributes()
    {
        $header = ComponentFactory::make(ComponentEnum::H2)
            ->setAttribute('id', 'nice_header');

        $this->blade('{{ $header }}', [
            'header' => $header,
        ])
            ->assertSee('id="nice_header"', false);
    }

    #[Test]
    public function h2_header_accepts_theme()
    {
        $theme = [
            'color' => 'error',
        ];

        $header = ComponentFactory::make(ComponentEnum::H2)
            ->setThemes($theme);

        $this->blade('{{ $header }}', [
            'header' => $header,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
