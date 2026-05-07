<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Form;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class DataListTest extends TestCase
{
    #[Test]
    public function empty_datalist()
    {
        $datalist = ComponentBuilder::make(ComponentEnum::DATALIST);

        $this->blade('{{ $datalist }}', [
            'datalist' => $datalist,
        ])
            ->assertSee('<datalist', false)
            ->assertSee('</datalist>', false);
    }

    #[Test]
    public function datalist_accepts_content()
    {
        $datalist = ComponentBuilder::make(ComponentEnum::DATALIST)
            ->setContent(
                ComponentBuilder::make(ComponentEnum::OPTION)
                    ->setContent('Option content')
                    ->setAttribute('value', 'option_value')
            );

        $this->blade('{{ $datalist }}', [
            'datalist' => $datalist,
        ])
            ->assertSee('<option', false)
            ->assertSee('value="option_value"', false)
            ->assertSee('</option>', false);
    }

    #[Test]
    public function datalist_accepts_contents_array()
    {
        $datalist = ComponentBuilder::make(ComponentEnum::DATALIST)
            ->setContents([
                ComponentBuilder::make(ComponentEnum::OPTION)
                    ->setAttribute('value', 'option_value'),
                ComponentBuilder::make(ComponentEnum::OPTION)
                    ->setAttribute('value', 'option_value_2'),
            ]);

        $this->blade('{{ $datalist }}', [
            'datalist' => $datalist,
        ])
            ->assertSee('<datalist', false)
            ->assertSee('<option', false)
            ->assertSee('value="option_value"', false)
            ->assertSee('value="option_value_2', false)
            ->assertSee('</option>', false)
            ->assertSee('</datalist>', false);
    }

    #[Test]
    public function datalist_accepts_attributes()
    {
        $datalist = ComponentBuilder::make(ComponentEnum::DATALIST)
            ->setAttribute('id', 'browsers');

        $this->blade('{{ $datalist }}', [
            'datalist' => $datalist,
        ])
            ->assertSee('id="browsers"', false);
    }

    #[Test]
    public function datalist_accepts_theme()
    {
        $theme = [
            'display' => 'inline-block',
        ];

        $datalist = ComponentBuilder::make(ComponentEnum::DATALIST)
            ->setThemes($theme);

        $this->blade('{{ $datalist }}', [
            'datalist' => $datalist,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
