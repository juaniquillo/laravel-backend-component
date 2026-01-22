<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Form;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class LabelTest extends TestCase
{
    #[Test]
    public function simple_label()
    {
        $label = ComponentBuilder::make(ComponentEnum::LABEL);

        $this->blade('{{ $label }}', [
            'label' => $label,
        ])
            ->assertSee('<label', false);
    }

    #[Test]
    public function label_accepts_content()
    {
        $label = ComponentBuilder::make(ComponentEnum::LABEL)
            ->setContent(
                ComponentBuilder::make(ComponentEnum::PARAGRAPH)
                    ->setContent('Span content')
            );

        $this->blade('{{ $label }}', [
            'label' => $label,
        ])
            ->assertSee('<label', false)
            ->assertSee('Span content')
            ->assertSee('</label>', false);
    }

    #[Test]
    public function label_accepts_contents_array()
    {
        $header = ComponentBuilder::make(ComponentEnum::LABEL)
            ->setContents([
                'content 1 ',
                'content 2',
            ]);

        $this->blade('{{ $header }}', [
            'header' => $header,
        ])
            ->assertSee('<label', false)
            ->assertSee('content 1 ')
            ->assertSee('content 2')
            ->assertSee('</label>', false);
    }

    #[Test]
    public function label_accepts_attributes()
    {
        $label = ComponentBuilder::make(ComponentEnum::LABEL)
            ->setAttribute('for', 'label_for');

        $this->blade('{{ $label }}', [
            'label' => $label,
        ])
            ->assertSee('for="label_for"', false);
    }

    #[Test]
    public function label_accepts_theme()
    {
        $theme = [
            'color' => 'default',
        ];

        $label = ComponentBuilder::make(ComponentEnum::LABEL)
            ->setThemes($theme);

        $this->blade('{{ $label }}', [
            'label' => $label,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
