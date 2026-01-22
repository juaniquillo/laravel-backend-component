<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Custom;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class ModalTest extends TestCase
{
    #[Test]
    public function simple_modal()
    {
        $modal = ComponentBuilder::make(ComponentEnum::MODAL);

        $this->blade('{{ $modal }}', [
            'modal' => $modal,
        ])
        // inertia's code
            ->assertSee('x-data="{ \'showModal\': false }"', false)
        // overlay classes
            ->assertSee('class="'.processThemes(['modal' => 'overlay']), false);
    }

    #[Test]
    public function modal_accepts_content()
    {
        $modal = ComponentBuilder::make(ComponentEnum::MODAL)
            ->setContent("This is the modal's content");

        $this->blade('{{ $modal }}', [
            'modal' => $modal,
        ])
            ->assertSee("This is the modal's content");
    }

    #[Test]
    public function modal_accepts_attributes()
    {
        $modal = ComponentBuilder::make(ComponentEnum::MODAL)
            ->setAttribute('id', 'my_modal');

        $this->blade('{{ $modal }}', [
            'modal' => $modal,
        ])
            ->assertSee('id="my_modal"', false);
    }

    #[Test]
    public function modal_accepts_theme()
    {
        $theme = ['modal' => 'default'];
        $modal = ComponentBuilder::make(ComponentEnum::MODAL)
            ->setThemes($theme);

        $this->blade('{{ $modal }}', [
            'modal' => $modal,
        ])
            ->assertSee('class="'.processThemes($theme), false);
    }

    #[Test]
    public function modal_accepts_button_slot()
    {
        $modal = ComponentBuilder::make(ComponentEnum::MODAL)
            ->setSlot(
                'button',
                ComponentBuilder::make(ComponentEnum::BUTTON)
                    ->setContent('Open Modal')
                    ->setAttribute('type', 'button')
                    ->setAttribute('@click', 'showModal = true')
            );

        $this->blade('{{ $modal }}', [
            'modal' => $modal,
        ])
            ->assertSee('<button', false)
            ->assertSee('@click="showModal = true"', false)
            ->assertSee('type="button"', false)
            ->assertSee('Open Modal')
            ->assertSee('</button>', false);
    }

    #[Test]
    public function modal_accepts_title_slot()
    {
        $modal = ComponentBuilder::make(ComponentEnum::MODAL)
            ->setSlot(
                'title',
                ComponentBuilder::make(ComponentEnum::DIV)
                    ->setContent('This is the title')
                    ->setAttribute('id', 'modal_title')
            );

        $this->blade('{{ $modal }}', [
            'modal' => $modal,
        ])
            ->assertSee('<div id="modal_title"', false)
            ->assertSee('This is the title');
    }

    #[Test]
    public function modal_accepts_footer_slot()
    {
        $modal = ComponentBuilder::make(ComponentEnum::MODAL)
            ->setSlot(
                'footer',
                ComponentBuilder::make(ComponentEnum::DIV)
                    ->setContent('This is the footer')
                    ->setAttribute('id', 'modal_footer')
            );

        $this->blade('{{ $modal }}', [
            'modal' => $modal,
        ])
            ->assertSee('<div id="modal_footer"', false)
            ->assertSee('This is the footer');
    }

    #[Test]
    public function modal_does_not_accept_arbitrary_slots()
    {
        $modal = ComponentBuilder::make(ComponentEnum::MODAL)
            ->setSlot(
                'arbitrary_slo',
                ComponentBuilder::make(ComponentEnum::DIV)
                    ->setContent('This is the arbitrary slot')
                    ->setAttribute('id', 'modal_arbitrary_slot')
            );

        $this->blade('{{ $modal }}', [
            'modal' => $modal,
        ])
            ->assertDontSee('<div id="modal_arbitrary_slot"', false)
            ->assertDontSee('This is the arbitrary slot');
    }
}
