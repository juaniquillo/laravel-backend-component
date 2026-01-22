<?php

declare(strict_types=1);

namespace Tests\Feature\Components;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class ParagraphTest extends TestCase
{
    #[Test]
    public function empty_paragraph()
    {
        $paragraph = ComponentBuilder::make(ComponentEnum::PARAGRAPH);

        $this->blade('{{ $paragraph }}', [
            'paragraph' => $paragraph,
        ])
            ->assertSee('<p', false)
            ->assertSee('</p>', false);
    }

    #[Test]
    public function paragraph_accepts_content()
    {
        $paragraph = ComponentBuilder::make(ComponentEnum::PARAGRAPH)
            ->setContent(
                ComponentBuilder::make(ComponentEnum::LINK)
                    ->setContent('Link content')
            );

        $this->blade('{{ $paragraph }}', [
            'paragraph' => $paragraph,
        ])
            ->assertSee('<p ', false)
            ->assertSee('<a', false)
            ->assertSee('Link content')
            ->assertSee('</a>', false)
            ->assertSee('</p>', false);
    }

    #[Test]
    public function paragraph_accepts_contents_array()
    {
        $paragraph = ComponentBuilder::make(ComponentEnum::PARAGRAPH)
            ->setContents([
                ComponentBuilder::make(ComponentEnum::SPAN)
                    ->setContent('Span'),
                ComponentBuilder::make(ComponentEnum::BOLD)
                    ->setContent('Bold'),
            ]);

        $this->blade('{{ $paragraph }}', [
            'paragraph' => $paragraph,
        ])
            ->assertSee('<p', false)
            ->assertSee('<span', false)
            ->assertSee('Span')
            ->assertSee('</span>', false)
            ->assertSee('<b', false)
            ->assertSee('Bold')
            ->assertSee('</b>', false)
            ->assertSee('</p>', false);
    }

    #[Test]
    public function paragraph_accepts_attributes()
    {
        $paragraph = ComponentBuilder::make(ComponentEnum::PARAGRAPH)
            ->setContent('Nice paragraph')
            ->setAttribute('id', 'paragraph_id');

        $this->blade('{{ $paragraph }}', [
            'paragraph' => $paragraph,
        ])
            ->assertSee('<p', false)
            ->assertSee('id="paragraph_id"', false)
            ->assertSee('</p>', false);
    }

    #[Test]
    public function paragraph_accepts_theme()
    {
        $theme = [
            'color' => 'success',
        ];

        $paragraph = ComponentBuilder::make(ComponentEnum::PARAGRAPH)
            ->setContent('Nice paragraph')
            ->setThemes($theme);

        $this->blade('{{ $paragraph }}', [
            'paragraph' => $paragraph,
        ])
            ->assertSee('<p', false)
            ->assertSee('class="'.processThemes($theme), false)
            ->assertSee('</p>', false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
