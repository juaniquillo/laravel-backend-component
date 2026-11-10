<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Form;

use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class SelectTest extends TestCase
{
    #[Test]
    public function empty_select()
    {
        $select = ComponentFactory::make(ComponentEnum::SELECT);

        $this->blade('{{ $select }}', [
            'select' => $select,
        ])
            ->assertSee('<select', false)
            ->assertSee('</select>', false);
    }

    #[Test]
    public function select_accepts_content()
    {
        $select = ComponentFactory::make(ComponentEnum::SELECT)
            ->setContent(
                ComponentFactory::make(ComponentEnum::OPTION)
                    ->setContent('Option content')
            );

        $this->blade('{{ $select }}', [
            'select' => $select,
        ])
            ->assertSee('<option', false)
            ->assertSee('Option content')
            ->assertSee('</option>', false);
    }

    #[Test]
    public function select_accepts_contents_array()
    {
        $header = ComponentFactory::make(ComponentEnum::SELECT)
            ->setContents([
                ComponentFactory::make(ComponentEnum::OPTION)
                    ->setContent('Option 1'),
                ComponentFactory::make(ComponentEnum::OPTION)
                    ->setContent('Option 2'),
            ]);

        $this->blade('{{ $header }}', [
            'header' => $header,
        ])
            ->assertSee('<select', false)
            ->assertSee('<option', false)
            ->assertSee('Option 1')
            ->assertSee('</option>', false)
            ->assertSee('Option 2')
            ->assertSee('</select>', false);
    }

    #[Test]
    public function select_accepts_attributes()
    {
        $select = ComponentFactory::make(ComponentEnum::SELECT)
            ->setAttribute('id', 'select_id');

        $this->blade('{{ $select }}', [
            'select' => $select,
        ])
            ->assertSee('id="select_id"', false);
    }

    #[Test]
    public function select_accepts_theme()
    {
        $theme = [
            'display' => 'inline-block',
        ];

        $select = ComponentFactory::make(ComponentEnum::SELECT)
            ->setThemes($theme);

        $this->blade('{{ $select }}', [
            'select' => $select,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
