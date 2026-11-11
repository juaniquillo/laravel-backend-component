<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Inline;

use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class IframeTest extends TestCase
{
    #[Test]
    public function empty_iframe()
    {
        $iframe = ComponentFactory::make(ComponentEnum::IFRAME);

        $this->blade('{{ $iframe }}', [
            'iframe' => $iframe,
        ])
            ->assertSee('<iframe', false)
            ->assertSee('</iframe>', false);
    }

    #[Test]
    public function iframe_accepts_content()
    {
        $iframe = ComponentFactory::make(ComponentEnum::IFRAME)
            ->setContent('Fallback content');

        $this->blade('{{ $iframe }}', [
            'iframe' => $iframe,
        ])
            ->assertSee('<iframe', false)
            ->assertSee('Fallback content')
            ->assertSee('</iframe>', false);
    }

    #[Test]
    public function iframe_accepts_contents_array()
    {
        $iframe = ComponentFactory::make(ComponentEnum::IFRAME)
            ->setContents([
                ComponentFactory::make(ComponentEnum::SPAN)
                    ->setContent('Span'),
                ComponentFactory::make(ComponentEnum::BOLD)
                    ->setContent('Bold'),
            ]);

        $this->blade('{{ $iframe }}', [
            'iframe' => $iframe,
        ])
            ->assertSee('<iframe', false)
            ->assertSee('<span', false)
            ->assertSee('Span')
            ->assertSee('</span>', false)
            ->assertSee('<b', false)
            ->assertSee('Bold')
            ->assertSee('</b>', false)
            ->assertSee('</iframe>', false);
    }

    #[Test]
    public function iframe_accepts_attributes()
    {
        $iframe = ComponentFactory::make(ComponentEnum::IFRAME)
            ->setAttribute('src', 'https://example.com')
            ->setAttribute('title', 'Example');

        $this->blade('{{ $iframe }}', [
            'iframe' => $iframe,
        ])
            ->assertSee('src="https://example.com"', false)
            ->assertSee('title="Example"', false);
    }

    #[Test]
    public function iframe_accepts_theme()
    {
        $theme = [
            'color' => 'success',
        ];

        $iframe = ComponentFactory::make(ComponentEnum::IFRAME)
            ->setThemes($theme);

        $this->blade('{{ $iframe }}', [
            'iframe' => $iframe,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
