<?php

declare(strict_types=1);

namespace Tests\Feature\Components;

use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ResolveComponentTest extends TestCase
{
    #[Test]
    public function alpine_style_attributes_are_rendered_as_is()
    {
        $div = ComponentFactory::make(ComponentEnum::DIV)
            ->setAttribute('@click', 'open = true')
            ->setAttribute('x-show', 'open');

        $this->blade('{{ $div }}', [
            'div' => $div,
        ])
            ->assertSee('@click="open = true"', false)
            ->assertSee('x-show="open"', false);
    }

    #[Test]
    public function settings_are_not_rendered_as_html_attributes()
    {
        $div = ComponentFactory::make(ComponentEnum::DIV)
            ->setSetting('transition', 'fade')
            ->setContent('Hello');

        $this->blade('{{ $div }}', [
            'div' => $div,
        ])
            ->assertSee('Hello', false)
            ->assertDontSee('settings', false)
            ->assertDontSee('transition', false);
    }

    #[Test]
    public function content_and_attributes_are_rendered_together()
    {
        $div = ComponentFactory::make(ComponentEnum::DIV)
            ->setAttribute('id', 'wrapper')
            ->setContent(
                ComponentFactory::make(ComponentEnum::PARAGRAPH)
                    ->setContent('Nested')
            );

        $this->blade('{{ $div }}', [
            'div' => $div,
        ])
            ->assertSee('<div id="wrapper"><p >Nested</p>', false);
    }
}
