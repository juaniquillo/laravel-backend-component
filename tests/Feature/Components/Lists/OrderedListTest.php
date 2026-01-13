<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Lists;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class OrderedListTest extends TestCase
{
    #[Test]
    public function empty_ordered_list()
    {
        $orderedList = ComponentBuilder::make(ComponentEnum::OL);

        $this->blade('{{ $orderedList }}', [
            'orderedList' => $orderedList,
        ])
            ->assertSee('<ol', false)
            ->assertSee('</ol>', false);
    }

    #[Test]
    public function ordered_list_accepts_content()
    {
        $orderedList = ComponentBuilder::make(ComponentEnum::OL)
            ->setContent(
                ComponentBuilder::make(ComponentEnum::LI)
                    ->setContent('List content')
            );

        $this->blade('{{ $orderedList }}', [
            'orderedList' => $orderedList,
        ])
            ->assertSee('<li', false)
            ->assertSee('List content')
            ->assertSee('</li>', false);
    }

    #[Test]
    public function ordered_list_accepts_contents_array()
    {
        $ordered_list = ComponentBuilder::make(ComponentEnum::OL)
            ->setContents([
                ComponentBuilder::make(ComponentEnum::LI)
                    ->setContent('First item'),
                ComponentBuilder::make(ComponentEnum::LI)
                    ->setContent('Second item'),
            ]);

        $this->blade('{{ $ordered_list }}', [
            'ordered_list' => $ordered_list,
        ])
            ->assertSee('<ol', false)
            ->assertSee('<li', false)
            ->assertSee('First item')
            ->assertSee('</li>', false)
            ->assertSee('Second item')
            ->assertSee('</ol>', false);
    }

    #[Test]
    public function ordered_list_accepts_attributes()
    {
        $orderedList = ComponentBuilder::make(ComponentEnum::OL)
            ->setAttribute('id', 'list_id');

        $this->blade('{{ $orderedList }}', [
            'orderedList' => $orderedList,
        ])
            ->assertSee('id="list_id"', false);
    }

    #[Test]
    public function ordered_list_accepts_theme()
    {
        $theme = [
            'lists' => 'ordered',
        ];

        $orderedList = ComponentBuilder::make(ComponentEnum::OL)
            ->setThemes($theme);

        $this->blade('{{ $orderedList }}', [
            'orderedList' => $orderedList,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
