<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Table;

use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class TableTest extends TestCase
{
    #[Test]
    public function empty_table()
    {
        $table = ComponentFactory::make(ComponentEnum::TABLE);

        $this->blade('{{ $table }}', [
            'table' => $table,
        ])
            ->assertSee('<table', false)
            ->assertSee('</table>', false);
    }

    #[Test]
    public function table_accepts_content()
    {
        $table = ComponentFactory::make(ComponentEnum::TABLE)
            ->setContent(
                ComponentFactory::make(ComponentEnum::TBODY)
            );

        $this->blade('{{ $table }}', [
            'table' => $table,
        ])
            ->assertSee('<tbody', false)
            ->assertSee('</tbody>', false);
    }

    #[Test]
    public function table_accepts_contents_array()
    {
        $table = ComponentFactory::make(ComponentEnum::TABLE)
            ->setContents([
                ComponentFactory::make(ComponentEnum::THEAD),
                ComponentFactory::make(ComponentEnum::TBODY),
            ]);

        $this->blade('{{ $table }}', [
            'table' => $table,
        ])
            ->assertSee('<thead', false)
            ->assertSee('</thead>', false)
            ->assertSee('<tbody', false)
            ->assertSee('</tbody>', false);
    }

    #[Test]
    public function table_accepts_attributes()
    {
        $table = ComponentFactory::make(ComponentEnum::TABLE)
            ->setAttribute('id', 'table_id');

        $this->blade('{{ $table }}', [
            'table' => $table,
        ])
            ->assertSee('id="table_id"', false);
    }

    #[Test]
    public function table_accepts_theme()
    {
        $theme = [
            'size' => 'w-full',
        ];

        $table = ComponentFactory::make(ComponentEnum::TABLE)
            ->setThemes($theme);

        $this->blade('{{ $table }}', [
            'table' => $table,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
