<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Inline;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class BoldTest extends TestCase
{
    #[Test]
    public function simple_empty_bold()
    {
        $bold = ComponentBuilder::make(ComponentEnum::BOLD);

        $this->blade('{{ $bold }}', [
            'bold' => $bold,
        ])
            ->assertSee('<b', false)
            ->assertSee('</b>', false);
    }

    #[Test]
    public function bold_accepts_content()
    {
        $bold = ComponentBuilder::make(ComponentEnum::BOLD)
            ->setContent('Nice b tag');

        $this->blade('{{ $bold }}', [
            'bold' => $bold,
        ])
            ->assertSee('Nice b tag');
    }

    #[Test]
    public function bold_accepts_contents_array()
    {
        $bold = ComponentBuilder::make(ComponentEnum::BOLD)
            ->setContents([
                'content 1 ',
                'content 2',
            ]);

        $this->blade('{{ $bold }}', [
            'bold' => $bold,
        ])
            ->assertSee('<b', false)
            ->assertSee('content 1 ')
            ->assertSee('content 2')
            ->assertSee('</b>', false);
    }

    #[Test]
    public function bold_accepts_attributes()
    {
        $bold = ComponentBuilder::make(ComponentEnum::BOLD)
            ->setAttribute('id', 'nice_bold');

        $this->blade('{{ $bold }}', [
            'bold' => $bold,
        ])
            ->assertSee('id="nice_bold"', false);
    }

    #[Test]
    public function bold_accepts_theme()
    {
        $theme = [
            'display' => 'inline-block',
        ];

        $bold = ComponentBuilder::make(ComponentEnum::BOLD)
            ->setThemes($theme);

        $this->blade('{{ $bold }}', [
            'bold' => $bold,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
