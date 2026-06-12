<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Custom;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Utils\ModalUtil;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\processThemes;

class ModalTest extends TestCase
{
    #[Test]
    public function simple_modal()
    {
        $modal = ModalUtil::make(content: '')->getComponent();

        $this->blade('{{ $modal }}', [
            'modal' => $modal,
        ])
            ->assertSee('x-data="{ showModal: false }"', false)
            ->assertSee('class="'.processThemes(['modal' => 'overlay']), false);
    }

    #[Test]
    public function modal_accepts_content()
    {
        $modal = ModalUtil::make(content: "This is the modal's content")->getComponent();

        $this->blade('{{ $modal }}', [
            'modal' => $modal,
        ])
            ->assertSee("This is the modal's content");
    }

    #[Test]
    public function modal_accepts_attributes()
    {
        $modal = ModalUtil::make(content: '')
            ->setAttribute('id', 'my_modal')
            ->getComponent();

        $this->blade('{{ $modal }}', [
            'modal' => $modal,
        ])
            ->assertSee('id="my_modal"', false);
    }

    #[Test]
    public function modal_accepts_theme()
    {
        $theme = ['modal' => 'default'];
        $modal = ModalUtil::make(content: '')
            ->setThemes($theme)
            ->getComponent();

        $this->blade('{{ $modal }}', [
            'modal' => $modal,
        ])
            ->assertSee('class="'.processThemes($theme), false);
    }

    #[Test]
    public function modal_accepts_button()
    {
        $modal = ModalUtil::make(
            content: '',
            button: ComponentBuilder::make(ComponentEnum::BUTTON)
                ->setContent('Open Modal')
                ->setAttribute('type', 'button')
                ->setAttribute('@click', 'showModal = true'),
        )->getComponent();

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
    public function modal_accepts_title()
    {
        $modal = ModalUtil::make(
            content: '',
            title: ComponentBuilder::make(ComponentEnum::DIV)
                ->setContent('This is the title')
                ->setAttribute('id', 'modal_title'),
        )->getComponent();

        $this->blade('{{ $modal }}', [
            'modal' => $modal,
        ])
            ->assertSee('<div id="modal_title"', false)
            ->assertSee('This is the title');
    }

    #[Test]
    public function modal_accepts_footer()
    {
        $modal = ModalUtil::make(
            content: '',
            footer: ComponentBuilder::make(ComponentEnum::DIV)
                ->setContent('This is the footer')
                ->setAttribute('id', 'modal_footer'),
        )->getComponent();

        $this->blade('{{ $modal }}', [
            'modal' => $modal,
        ])
            ->assertSee('<div id="modal_footer"', false)
            ->assertSee('This is the footer');
    }
}
