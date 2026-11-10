<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Inline;

use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class ItalicTest extends TestCase
{
    #[Test]
    public function simple_italic()
    {
        $italic = ComponentFactory::make(ComponentEnum::ITALIC);

        $this->blade('{{ $italic }}', [
            'italic' => $italic,
        ])
            ->assertSee('<i', false)
            ->assertSee('</i>', false);
    }

    #[Test]
    public function italic_accepts_content()
    {
        $italic = ComponentFactory::make(ComponentEnum::ITALIC)
            ->setContent('Nice i tag');

        $this->blade('{{ $italic }}', [
            'italic' => $italic,
        ])
            ->assertSee('Nice i tag');
    }

    #[Test]
    public function italic_accepts_contents_array()
    {
        $italic = ComponentFactory::make(ComponentEnum::ITALIC)
            ->setContents([
                'content 1 ',
                'content 2',
            ]);

        $this->blade('{{ $italic }}', [
            'italic' => $italic,
        ])
            ->assertSee('<i', false)
            ->assertSee('content 1 ')
            ->assertSee('content 2')
            ->assertSee('</i>', false);
    }

    #[Test]
    public function italic_accepts_attributes()
    {
        $italic = ComponentFactory::make(ComponentEnum::ITALIC)
            ->setAttribute('id', 'nice_italic');

        $this->blade('{{ $italic }}', [
            'italic' => $italic,
        ])
            ->assertSee('id="nice_italic"', false);
    }

    #[Test]
    public function italic_accepts_theme()
    {
        $theme = [
            'display' => 'inline-block',
        ];

        $italic = ComponentFactory::make(ComponentEnum::ITALIC)
            ->setThemes($theme);

        $this->blade('{{ $italic }}', [
            'italic' => $italic,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
