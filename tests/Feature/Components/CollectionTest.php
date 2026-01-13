<?php

declare(strict_types=1);

namespace Tests\Feature\Components;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class CollectionTest extends TestCase
{
    #[Test]
    public function empty_collection()
    {
        $collection = ComponentBuilder::make(ComponentEnum::COLLECTION);

        $this->blade('{{ $collection }}', [
            'collection' => $collection,
        ])
            ->assertSee('', false);
    }

    #[Test]
    public function collection_accepts_content()
    {
        $collection = ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContent(
                ComponentBuilder::make(ComponentEnum::PARAGRAPH)
                    ->setContent('This is the content content')
            );

        $this->blade('{{ $collection }}', [
            'collection' => $collection,
        ])
            ->assertSee('<p', false)
            ->assertSee('This is the content content')
            ->assertSee('</p>', false);

    }

    #[Test]
    public function collection_accepts_contents_array()
    {
        $collection = ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setContents([
                ComponentBuilder::make(ComponentEnum::SPAN)
                    ->setContent('Span'),
                ComponentBuilder::make(ComponentEnum::BOLD)
                    ->setContent('Bold'),
            ]);

        $this->blade('{{ $collection }}', [
            'collection' => $collection,
        ])
            ->assertSee('<span', false)
            ->assertSee('Span')
            ->assertSee('</span>', false)
            ->assertSee('<b', false)
            ->assertSee('Bold')
            ->assertSee('</b>', false);
    }

    #[Test]
    public function collection_does_not_accept_attributes()
    {
        $collection = ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setAttribute('id', 'collection_id');

        $this->blade('{{ $collection }}', [
            'collection' => $collection,
        ])
            ->assertDontSee('id="collection_id"', false);
    }

    #[Test]
    public function collection_accepts_theme()
    {
        $theme = [
            'color' => 'success',
        ];

        $collection = ComponentBuilder::make(ComponentEnum::COLLECTION)
            ->setThemes($theme);

        $this->blade('{{ $collection }}', [
            'collection' => $collection,
        ])
            ->assertDontSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
