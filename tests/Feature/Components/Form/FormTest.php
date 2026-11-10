<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Form;

use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class FormTest extends TestCase
{
    #[Test]
    public function simple_form()
    {
        $form = ComponentFactory::make(ComponentEnum::FORM);

        $this->blade('{{ $form }}', [
            'form' => $form,
        ])
            ->assertSee('<form', false)
            ->assertSee('</form>', false);
    }

    #[Test]
    public function form_accepts_content()
    {
        $form = ComponentFactory::make(ComponentEnum::FORM)
            ->setContent(
                ComponentFactory::make(ComponentEnum::BUTTON)
                    ->setContent('Send Form')
            );

        $this->blade('{{ $form }}', [
            'form' => $form,
        ])
            ->assertSee('<button', false)
            ->assertSee('Send Form')
            ->assertSee('</button>', false);
    }

    #[Test]
    public function form_accepts_contents_array()
    {
        $header = ComponentFactory::make(ComponentEnum::FORM)
            ->setContents([
                ComponentFactory::make(ComponentEnum::TEXT_INPUT),
                ComponentFactory::make(ComponentEnum::EMAIL_INPUT),
                ComponentFactory::make(ComponentEnum::BUTTON)
                    ->setContent('Send Form'),
            ]);

        $this->blade('{{ $header }}', [
            'header' => $header,
        ])
            ->assertSee('<form', false)
            ->assertSee('<input type="text"', false)
            ->assertSee('<input type="email"', false)
            ->assertSee('<button', false)
            ->assertSee('Send Form')
            ->assertSee('</button>', false)
            ->assertSee('</form>', false);
    }

    #[Test]
    public function form_accepts_attributes()
    {
        $form = ComponentFactory::make(ComponentEnum::FORM)
            ->setAttribute('method', 'post')
            ->setAttribute('action', '/')
            ->setAttribute('enctype', 'multipart/form-data');

        $this->blade('{{ $form }}', [
            'form' => $form,
        ])
            ->assertSee('method="POST"', false)
            ->assertSee('action="/"', false)
            ->assertSee('enctype="multipart/form-data"', false);
    }

    #[Test]
    public function form_accepts_theme()
    {
        $theme = [
            'display' => 'flex',
        ];

        $form = ComponentFactory::make(ComponentEnum::FORM)
            ->setThemes($theme);

        $this->blade('{{ $form }}', [
            'form' => $form,
        ])
            ->assertSee('class="'.processThemes($theme), false);

        $this->assertNotEmpty(processThemes($theme));
    }

    #[Test]
    public function form_accepts_some_settings()
    {

        $form = ComponentFactory::make(ComponentEnum::FORM);

        $this->blade('{{ $form }}', [
            'form' => $form,
        ])
            ->assertSee('<input type="hidden" name="_token"', false)
            ->assertSee('<input type="hidden" name="_method" value="POST"', false);

        $form->setSetting('disable_csrf', true)
            ->setSetting('disable_method_input', true);

        $this->assertEquals(true, $form->getSetting('disable_csrf'));
        $this->assertEquals(true, $form->getSetting('disable_method_input'));

        $form->unsetSetting('disable_csrf')
            ->unsetSetting('disable_method_input');

        $form->setSettings([
            'disable_csrf' => true,
            'disable_method_input' => true,
        ]);

        $this->blade('{{ $form }}', [
            'form' => $form,
        ])
            ->assertDontSee('<input type="hidden" name="_token"', false)
            ->assertDontSee('<input type="hidden" name="_method" value="POST"', false);

    }
}
