<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Inline;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class StrongTest extends TestCase
{
    #[Test]
    public function simple_strong()
    {
        $strong = ComponentBuilder::make(ComponentEnum::STRONG);

        $this->blade('{{ $strong }}', [
            'strong' => $strong,
        ])
            ->assertSee('<strong', false)
            ->assertSee('</strong>', false);
    }

    #[Test]
    public function strong_accepts_content()
    {
        $strong = ComponentBuilder::make(ComponentEnum::STRONG)
            ->setContent('Nice strong tag');

        $this->blade('{{ $strong }}', [
            'strong' => $strong,
        ])
            ->assertSee('Nice strong tag');
    }

    #[Test]
    public function strong_accepts_contents_array()
    {
        $strong = ComponentBuilder::make(ComponentEnum::STRONG)
            ->setContents([
                'content 1 ',
                'content 2',
            ]);

        $this->blade('{{ $strong }}', [
            'strong' => $strong,
        ])
            ->assertSee('<strong', false)
            ->assertSee('content 1 ')
            ->assertSee('content 2')
            ->assertSee('</strong>', false);
    }

    #[Test]
    public function strong_accepts_attributes()
    {
        $strong = ComponentBuilder::make(ComponentEnum::STRONG)
            ->setAttribute('id', 'nice_strong');

        $this->blade('{{ $strong }}', [
            'strong' => $strong,
        ])
            ->assertSee('id="nice_strong"', false);
    }

    #[Test]
    public function strong_accepts_theme()
    {
        $theme = [
            'display' => 'inline-block',
        ];

        $strong = ComponentBuilder::make(ComponentEnum::STRONG)
            ->setThemes($theme);

        $this->blade('{{ $strong }}', [
            'strong' => $strong,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
