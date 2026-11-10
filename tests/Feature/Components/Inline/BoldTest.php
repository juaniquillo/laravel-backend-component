<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Inline;

use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class BoldTest extends TestCase
{
    #[Test]
    public function simple_empty_bold()
    {
        $bold = ComponentFactory::make(ComponentEnum::BOLD);

        $this->blade('{{ $bold }}', [
            'bold' => $bold,
        ])
            ->assertSee('<b', false)
            ->assertSee('</b>', false);
    }

    #[Test]
    public function bold_accepts_content()
    {
        $bold = ComponentFactory::make(ComponentEnum::BOLD)
            ->setContent('Nice b tag');

        $this->blade('{{ $bold }}', [
            'bold' => $bold,
        ])
            ->assertSee('Nice b tag');
    }

    #[Test]
    public function bold_accepts_contents_array()
    {
        $bold = ComponentFactory::make(ComponentEnum::BOLD)
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
        $bold = ComponentFactory::make(ComponentEnum::BOLD)
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

        $bold = ComponentFactory::make(ComponentEnum::BOLD)
            ->setThemes($theme);

        $this->blade('{{ $bold }}', [
            'bold' => $bold,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
