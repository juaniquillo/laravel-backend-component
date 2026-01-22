<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Lists;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class UnorderedListTest extends TestCase
{
    #[Test]
    public function empty_unordered_list()
    {
        $unorderedList = ComponentBuilder::make(ComponentEnum::UL);

        $this->blade('{{ $unorderedList }}', [
            'unorderedList' => $unorderedList,
        ])
            ->assertSee('<ul', false)
            ->assertSee('</ul>', false);
    }

    #[Test]
    public function unordered_list_accepts_content()
    {
        $unorderedList = ComponentBuilder::make(ComponentEnum::UL)
            ->setContent(
                ComponentBuilder::make(ComponentEnum::LI)
                    ->setContent('List content')
            );

        $this->blade('{{ $unorderedList }}', [
            'unorderedList' => $unorderedList,
        ])
            ->assertSee('<li', false)
            ->assertSee('List content')
            ->assertSee('</li>', false);
    }

    #[Test]
    public function unordered_list_accepts_contents_array()
    {
        $unordered_list = ComponentBuilder::make(ComponentEnum::UL)
            ->setContents([
                ComponentBuilder::make(ComponentEnum::LI)
                    ->setContent('First item'),
                ComponentBuilder::make(ComponentEnum::LI)
                    ->setContent('Second item'),
            ]);

        $this->blade('{{ $unordered_list }}', [
            'unordered_list' => $unordered_list,
        ])
            ->assertSee('<ul', false)
            ->assertSee('<li', false)
            ->assertSee('First item')
            ->assertSee('</li>', false)
            ->assertSee('Second item')
            ->assertSee('</ul>', false);
    }

    #[Test]
    public function unordered_list_accepts_attributes()
    {
        $unorderedList = ComponentBuilder::make(ComponentEnum::UL)
            ->setAttribute('id', 'list_id');

        $this->blade('{{ $unorderedList }}', [
            'unorderedList' => $unorderedList,
        ])
            ->assertSee('id="list_id"', false);
    }

    #[Test]
    public function unordered_list_accepts_theme()
    {
        $theme = [
            'lists' => 'unordered',
        ];

        $unorderedList = ComponentBuilder::make(ComponentEnum::UL)
            ->setThemes($theme);

        $this->blade('{{ $unorderedList }}', [
            'unorderedList' => $unorderedList,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
