<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Inline;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class EmTest extends TestCase
{
    #[Test]
    public function simple_em()
    {
        $em = ComponentBuilder::make(ComponentEnum::EM);

        $this->blade('{{ $em }}', [
            'em' => $em,
        ])
            ->assertSee('<em', false)
            ->assertSee('</em>', false);
    }

    #[Test]
    public function em_accepts_content()
    {
        $em = ComponentBuilder::make(ComponentEnum::EM)
            ->setContent('Nice em tag');

        $this->blade('{{ $em }}', [
            'em' => $em,
        ])
            ->assertSee('Nice em tag');
    }

    #[Test]
    public function em_accepts_contents_array()
    {
        $em = ComponentBuilder::make(ComponentEnum::EM)
            ->setContents([
                'content 1 ',
                'content 2',
            ]);

        $this->blade('{{ $em }}', [
            'em' => $em,
        ])
            ->assertSee('<em', false)
            ->assertSee('content 1 ')
            ->assertSee('content 2')
            ->assertSee('</em>', false);
    }

    #[Test]
    public function em_accepts_attributes()
    {
        $em = ComponentBuilder::make(ComponentEnum::EM)
            ->setAttribute('id', 'nice_em');

        $this->blade('{{ $em }}', [
            'em' => $em,
        ])
            ->assertSee('id="nice_em"', false);
    }

    #[Test]
    public function em_accepts_theme()
    {
        $theme = [
            'display' => 'inline-block',
        ];

        $em = ComponentBuilder::make(ComponentEnum::EM)
            ->setThemes($theme);

        $this->blade('{{ $em }}', [
            'em' => $em,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
