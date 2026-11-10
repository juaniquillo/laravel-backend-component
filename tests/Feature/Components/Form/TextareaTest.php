<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Form;

use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class TextareaTest extends TestCase
{
    #[Test]
    public function simple_textarea()
    {
        $textarea = ComponentFactory::make(ComponentEnum::TEXTAREA);

        $this->blade('{{ $textarea }}', [
            'textarea' => $textarea,
        ])
            ->assertSee('<textarea', false);
    }

    #[Test]
    public function textarea_accepts_content()
    {
        $textarea = ComponentFactory::make(ComponentEnum::TEXTAREA)
            ->setContent('Textarea content');

        $this->blade('{{ $textarea }}', [
            'textarea' => $textarea,
        ])
            ->assertSee('<textarea', false)
            ->assertSee('Textarea content')
            ->assertSee('</textarea>', false);
    }

    #[Test]
    public function textarea_accepts_contents_array()
    {
        $header = ComponentFactory::make(ComponentEnum::TEXTAREA)
            ->setContents([
                'content 1 ',
                'content 2',
            ]);

        $this->blade('{{ $header }}', [
            'header' => $header,
        ])
            ->assertSee('<textarea', false)
            ->assertSee('content 1 ')
            ->assertSee('content 2')
            ->assertSee('</textarea>', false);
    }

    #[Test]
    public function textarea_accepts_attributes()
    {
        $textarea = ComponentFactory::make(ComponentEnum::TEXTAREA)
            ->setAttribute('for', 'textarea_for');

        $this->blade('{{ $textarea }}', [
            'textarea' => $textarea,
        ])
            ->assertSee('for="textarea_for"', false);
    }

    #[Test]
    public function textarea_accepts_theme()
    {
        $theme = [
            'display' => 'block',
        ];

        $textarea = ComponentFactory::make(ComponentEnum::TEXTAREA)
            ->setThemes($theme);

        $this->blade('{{ $textarea }}', [
            'textarea' => $textarea,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
