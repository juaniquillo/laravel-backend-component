<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Inline;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class ImgTest extends TestCase
{
    #[Test]
    public function simple_image()
    {
        $img = ComponentBuilder::make(ComponentEnum::IMG);

        $this->blade('{{ $img }}', [
            'img' => $img,
        ])
            ->assertSee('<img', false)
            ->assertSee('/>', false);
    }

    #[Test]
    public function image_has_no_content()
    {
        $img = ComponentBuilder::make(ComponentEnum::IMG)
            ->setContent('Nice image');

        $this->blade('{{ $img }}', [
            'img' => $img,
        ])
            ->assertDontSee('Nice image');
    }

    #[Test]
    public function image_accepts_attributes()
    {
        $img = ComponentBuilder::make(ComponentEnum::IMG)
            ->setAttribute('src', asset('path/to/image.jpg'))
            ->setAttribute('alt', 'Nice image');

        $this->blade('{{ $img }}', [
            'img' => $img,
        ])
            ->assertSee('<img', false)
            ->assertSee('src="'.asset('path/to/image.jpg').'"', false)
            ->assertSee('alt="Nice image"', false)
            ->assertSee('/>', false);
    }

    #[Test]
    public function image_accepts_theme()
    {
        $theme = [
            'display' => 'block',
        ];

        $img = ComponentBuilder::make(ComponentEnum::IMG)
            ->setAttribute('src', asset('path/to/image.jpg'))
            ->setAttribute('alt', 'Nice image')
            ->setThemes($theme);

        $this->blade('{{ $img }}', [
            'img' => $img,
        ])
            ->assertSee('<img', false)
            ->assertSee('class="'.processThemes($theme), false)
            ->assertSee('/>', false);

        $this->assertNotEmpty(processThemes($theme));
    }
}
