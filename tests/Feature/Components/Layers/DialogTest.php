<?php

declare(strict_types=1);

namespace Tests\Feature\Components\Layers;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DialogTest extends TestCase
{
    #[Test]
    public function empty_dialog()
    {
        $dialog = ComponentBuilder::make(ComponentEnum::DIALOG);

        $this->blade('{{ $dialog }}', [
            'dialog' => $dialog,
        ])
            ->assertSee('<dialog', false)
            ->assertSee('</dialog>', false);
    }

    #[Test]
    public function dialog_accepts_content()
    {
        $dialog = ComponentBuilder::make(ComponentEnum::DIALOG)
            ->setContent('Dialog content here');

        $this->blade('{{ $dialog }}', [
            'dialog' => $dialog,
        ])
            ->assertSee('<dialog >', false)
            ->assertSee('Dialog content here', false)
            ->assertSee('</dialog>', false);
    }

    #[Test]
    public function dialog_accepts_attributes()
    {
        $dialog = ComponentBuilder::make(ComponentEnum::DIALOG)
            ->setAttribute('id', 'my-dialog')
            ->setAttribute('open', 'open');

        $this->blade('{{ $dialog }}', [
            'dialog' => $dialog,
        ])
            ->assertSee('id="my-dialog"', false)
            ->assertSee('open="open"', false);
    }
}
