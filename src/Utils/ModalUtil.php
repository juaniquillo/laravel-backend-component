<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Utils;

use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\MainBackendComponent;
use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;

final class ModalUtil
{
    /**
     * @var array<string, int|string|null>
     */
    private array $attributes = [];

    /**
     * @var array<string, string|array<string|int, string>>
     */
    private array $themes = [];

    private function __construct(
        private readonly string|int|CompoundComponent $content,
        private readonly ?CompoundComponent $button = null,
        private readonly ?CompoundComponent $title = null,
        private readonly ?CompoundComponent $footer = null,
        private readonly ?CompoundComponent $overlay = null,
        private readonly ThemeManager $themeManager = new DefaultThemeManager,
    ) {}

    public static function make(
        string|int|CompoundComponent $content,
        ?CompoundComponent $button = null,
        ?CompoundComponent $title = null,
        ?CompoundComponent $footer = null,
        ?CompoundComponent $overlay = null,
    ): static {
        return new self($content, $button, $title, $footer, $overlay);
    }

    public function setAttribute(string $name, int|string|null $value): static
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * @param  array<string, int|string|null>  $attributes
     */
    public function setAttributes(array $attributes): static
    {
        foreach ($attributes as $name => $value) {
            $this->setAttribute($name, $value);
        }

        return $this;
    }

    /**
     * @param  string|array<string|int, string>  $theme
     */
    public function setTheme(string $name, string|array $theme): static
    {
        $this->themes[$name] = $theme;

        return $this;
    }

    /**
     * @param  array<string, string|array<string|int, string>>  $themes
     */
    public function setThemes(array $themes): static
    {
        foreach ($themes as $name => $theme) {
            $this->setTheme($name, $theme);
        }

        return $this;
    }

    public function getComponent(): CompoundComponent
    {
        // Overlay content
        $overlayContent = $this->overlay ?? (new MainBackendComponent(ComponentEnum::DIV, $this->themeManager))
            ->setTheme('modal', 'overlay');

        // Overlay wrapper with transitions
        $overlayWrapper = new MainBackendComponent(ComponentEnum::DIV, $this->themeManager);
        $overlayWrapper->setAttributes([
            'x-show' => 'showModal',
            'class' => 'fixed inset-0 transform transition-all',
            'x-on:click' => 'showModal = false',
            'x-on:keydown.escape.window' => 'show = false',
            'x-transition:enter' => 'ease-out duration-300',
            'x-transition:enter-start' => 'opacity-0',
            'x-transition:enter-end' => 'opacity-100',
            'x-transition:leave' => 'ease-in duration-200',
            'x-transition:leave-start' => 'opacity-100',
            'x-transition:leave-end' => 'opacity-0',
        ]);
        $overlayWrapper->setContent($overlayContent);

        // Inner content div (user themes + attributes)
        $innerContentDiv = new MainBackendComponent(ComponentEnum::DIV, $this->themeManager);

        if ($this->themes !== []) {
            $innerContentDiv->setThemes($this->themes);
        } else {
            $innerContentDiv->setTheme('modal', [
                'default',
                'xl',
            ]);
        }

        if ($this->attributes !== []) {
            $innerContentDiv->setAttributes($this->attributes);
        }

        // Inner contents: title + main content + footer
        /** @var array<string|int, string|int|CompoundComponent> $innerItems */
        $innerItems = [];

        if ($this->title !== null) {
            $innerItems['title'] = $this->title;
        }

        $content = $this->content;

        if (\is_string($content) || \is_int($content)) {
            $content = (new MainBackendComponent(ComponentEnum::DIV, $this->themeManager))
                ->setContent($content)
                ->setTheme('padding', 'sm');
        }

        $innerItems[] = $content;

        if ($this->footer !== null) {
            $innerItems['footer'] = $this->footer;
        }

        $innerContentDiv->setContents(contents: $innerItems, overwrite: true);

        // Content wrapper with trap and transitions
        $contentWrapper = new MainBackendComponent(ComponentEnum::DIV, $this->themeManager);
        $contentWrapper->setAttributes([
            'x-trap.noscroll' => 'showModal',
            'x-transition:enter' => 'ease-out duration-300',
            'x-transition:enter-start' => 'opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95',
            'x-transition:enter-end' => 'opacity-100 translate-y-0 sm:scale-100',
            'x-transition:leave' => 'ease-in duration-200',
            'x-transition:leave-start' => 'opacity-100 translate-y-0 sm:scale-100',
            'x-transition:leave-end' => 'opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95',
        ]);
        $contentWrapper->setContent($innerContentDiv);

        // Modal wrapper (x-show, x-cloak, positioning)
        $modalWrapper = new MainBackendComponent(ComponentEnum::DIV, $this->themeManager);
        $modalWrapper->setAttributes([
            'x-show' => 'showModal',
            'x-cloak' => '',
            'class' => 'fixed inset-0 overflow-y-auto px-4 py-6 z-50',
        ]);
        $modalWrapper->setContents([$overlayWrapper, $contentWrapper]);

        // Root div (Alpine data)
        $root = new MainBackendComponent(ComponentEnum::DIV, $this->themeManager);
        $root->setAttributes([
            'x-data' => '{ showModal: false }',
            'x-on:keydown.escape' => 'showModal=false',
            ':aria-hidden' => '!showModal',
        ]);

        /** @var array<string|int, string|int|CompoundComponent> $rootContents */
        $rootContents = [];

        $button = $this->button ?? (new MainBackendComponent(ComponentEnum::BUTTON, $this->themeManager))
            ->setContent('Open Modal')
            ->setAttribute('type', 'button')
            ->setAttribute('@click', 'showModal = true')
            ->setTheme('action', 'info')
            ->setTheme('padding', 'button-compact')
            ->setTheme('border-radius', 'sm');

        $rootContents[] = $button;

        $rootContents[] = $modalWrapper;
        $root->setContents($rootContents);

        return $root;
    }
}
