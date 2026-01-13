<?php

declare(strict_types=1);

namespace Test\Feature\Components\Table;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class TableTest extends TestCase
{
    #[Test]
    public function empty_table()
    {
        $table = ComponentBuilder::make(ComponentEnum::TABLE);

        $this->blade('{{ $table }}', [
            'table' => $table,
        ])
            ->assertSee('<table', false)
            ->assertSee('</table>', false);
    }

    #[Test]
    public function table_accepts_content()
    {
        $table = ComponentBuilder::make(ComponentEnum::TABLE)
            ->setContent(
                ComponentBuilder::make(ComponentEnum::TBODY)
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
        $table = ComponentBuilder::make(ComponentEnum::TABLE)
            ->setContents([
                ComponentBuilder::make(ComponentEnum::THEAD),
                ComponentBuilder::make(ComponentEnum::TBODY),
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
        $table = ComponentBuilder::make(ComponentEnum::TABLE)
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

        $table = ComponentBuilder::make(ComponentEnum::TABLE)
            ->setThemes($theme);

        $this->blade('{{ $table }}', [
            'table' => $table,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
