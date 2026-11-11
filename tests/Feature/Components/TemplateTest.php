<?php

declare(strict_types=1);

namespace Tests\Feature\Components;

use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class TemplateTest extends TestCase
{
    #[Test]
    public function empty_template()
    {
        $template = ComponentFactory::make(ComponentEnum::TEMPLATE);

        $this->blade('{{ $template }}', [
            'template' => $template,
        ])
            ->assertSee('<template', false)
            ->assertSee('</template>', false);
    }

    #[Test]
    public function template_accepts_content()
    {
        $template = ComponentFactory::make(ComponentEnum::TEMPLATE)
            ->setContent(
                ComponentFactory::make(ComponentEnum::PARAGRAPH)
                    ->setContent('This is the content content')
            );

        $this->blade('{{ $template }}', [
            'template' => $template,
        ])
            ->assertSee('<template', false)
            ->assertSee('This is the content content')
            ->assertSee('</template>', false);
    }

    #[Test]
    public function template_accepts_contents_array()
    {
        $template = ComponentFactory::make(ComponentEnum::TEMPLATE)
            ->setContents([
                ComponentFactory::make(ComponentEnum::SPAN)
                    ->setContent('Span'),
                ComponentFactory::make(ComponentEnum::BOLD)
                    ->setContent('Bold'),
            ]);

        $this->blade('{{ $template }}', [
            'template' => $template,
        ])
            ->assertSee('<template', false)
            ->assertSee('<span', false)
            ->assertSee('Span')
            ->assertSee('</span>', false)
            ->assertSee('<b', false)
            ->assertSee('Bold')
            ->assertSee('</b>', false)
            ->assertSee('</template>', false);
    }

    #[Test]
    public function template_accepts_attributes()
    {
        $template = ComponentFactory::make(ComponentEnum::TEMPLATE)
            ->setAttribute('id', 'template_id');

        $this->blade('{{ $template }}', [
            'template' => $template,
        ])
            ->assertSee('id="template_id"', false);
    }

    #[Test]
    public function template_accepts_theme()
    {
        $theme = [
            'color' => 'success',
        ];

        $template = ComponentFactory::make(ComponentEnum::TEMPLATE)
            ->setThemes($theme);

        $this->blade('{{ $template }}', [
            'template' => $template,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
