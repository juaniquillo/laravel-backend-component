<?php

declare(strict_types=1);

namespace Feature\Components\Details;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class DetailsTest extends TestCase
{
    #[Test]
    public function empty_details()
    {
        $details = ComponentBuilder::make(ComponentEnum::DETAILS);

        $this->blade('{{ $details }}', [
            'details' => $details,
        ])
            ->assertSee('<details', false)
            ->assertSee('</details>', false);
    }

    #[Test]
    public function details_accepts_content()
    {
        $details = ComponentBuilder::make(ComponentEnum::DETAILS)
            ->setContent(
                ComponentBuilder::make(ComponentEnum::SUMMARY)
                    ->setContent('This is the summary content')
            )
            ->setContent(
                ComponentBuilder::make(ComponentEnum::PARAGRAPH)
                    ->setContent('This is the content content')
            );

        $this->blade('{{ $details }}', [
            'details' => $details,
        ])
            ->assertSee('<details >', false)
            ->assertSee('<summary >This is the summary content</summary>', false)
            ->assertSee('<p >This is the content content</p>', false)
            ->assertSee('</details>', false);
    }

    #[Test]
    public function details_accepts_contents_array()
    {
        $details = ComponentBuilder::make(ComponentEnum::DETAILS)
            ->setContents([
                ComponentBuilder::make(ComponentEnum::SUMMARY)
                    ->setContent('This is the summary content'),
                ComponentBuilder::make(ComponentEnum::PARAGRAPH)
                    ->setContent('This is the content content'),
            ]);

        $this->blade('{{ $details }}', [
            'details' => $details,
        ])
            ->assertSee('<details >', false)
            ->assertSee('<summary >This is the summary content</summary>', false)
            ->assertSee('<p >This is the content content</p>', false)
            ->assertSee('</details>', false);
    }

    #[Test]
    public function details_accepts_attributes()
    {
        $details = ComponentBuilder::make(ComponentEnum::DETAILS)
            ->setAttribute('open', 'open')
            ->setAttribute('name', 'custom_details');

        $this->blade('{{ $details }}', [
            'details' => $details,
        ])
            ->assertSee('open="open"', false)
            ->assertSee('name="custom_details"', false);
    }

    #[Test]
    public function details_accepts_theme()
    {
        $theme = [
            'background' => 'default',
        ];

        $details = ComponentBuilder::make(ComponentEnum::DETAILS)
            ->setThemes($theme);

        $this->blade('{{ $details }}', [
            'details' => $details,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
