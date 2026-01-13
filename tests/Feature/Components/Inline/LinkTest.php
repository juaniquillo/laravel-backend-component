<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Inline;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class LinkTest extends TestCase
{
    #[Test]
    public function simple_link()
    {
        $link = ComponentBuilder::make(ComponentEnum::LINK);

        $this->blade('{{ $link }}', [
            'link' => $link,
        ])
            ->assertSee('<a ', false)
            ->assertSee('</a>', false);
    }

    #[Test]
    public function link_accepts_content()
    {
        $link = ComponentBuilder::make(ComponentEnum::LINK)
            ->setContent(
                ComponentBuilder::make(ComponentEnum::SPAN)
                    ->setContent('Span content')
            );

        $this->blade('{{ $link }}', [
            'link' => $link,
        ])
            ->assertSee('<a ', false)
            ->assertSee('<span', false)
            ->assertSee('Span content')
            ->assertSee('</span>', false)
            ->assertSee('</a>', false);
    }

    #[Test]
    public function link_accepts_contents_array()
    {
        $link = ComponentBuilder::make(ComponentEnum::LINK)
            ->setContents([
                ComponentBuilder::make(ComponentEnum::SPAN)
                    ->setContent('Span'),
                ComponentBuilder::make(ComponentEnum::BOLD)
                    ->setContent('Bold'),
            ]);

        $this->blade('{{ $link }}', [
            'link' => $link,
        ])
            ->assertSee('<a', false)
            ->assertSee('<span', false)
            ->assertSee('Span')
            ->assertSee('</span>', false)
            ->assertSee('<b', false)
            ->assertSee('Bold')
            ->assertSee('</b>', false)
            ->assertSee('</a>', false);
    }

    #[Test]
    public function link_accepts_attributes()
    {
        $link = ComponentBuilder::make(ComponentEnum::LINK)
            ->setContent('Nice link')
            ->setAttribute('target', '_blank');

        $this->blade('{{ $link }}', [
            'link' => $link,
        ])
            ->assertSee('<a', false)
            ->assertSee('target="_blank"', false)
            ->assertSee('</a>', false);
    }

    #[Test]
    public function link_accepts_theme()
    {
        $theme = [
            'action' => 'default',
        ];

        $link = ComponentBuilder::make(ComponentEnum::LINK)
            ->setContent('Nice link')
            ->setThemes($theme);

        $this->blade('{{ $link }}', [
            'link' => $link,
        ])
            ->assertSee('<a', false)
            ->assertSee('class="'.processThemes($theme), false)
            ->assertSee('</a>', false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
