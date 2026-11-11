<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;
use Juaniquillo\BackendComponents\Components\DefaultAttributeBag;
use Juaniquillo\BackendComponents\Concerns\HasContent;
use Juaniquillo\BackendComponents\Concerns\HasPath;
use Juaniquillo\BackendComponents\Concerns\IsBackendComponent;
use Juaniquillo\BackendComponents\Concerns\IsLivewireComponent;
use Juaniquillo\BackendComponents\Contracts\LivewireComponent;
use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;
use Juaniquillo\BaseBackendComponent\Concerns\HasSettings;
use Juaniquillo\BaseBackendComponent\Concerns\IsThemeable;
use Juaniquillo\BaseBackendComponent\Contracts\AttributeBag;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;
use Juaniquillo\BaseBackendComponent\Contracts\ThemeManager;

/**
 * @phpstan-import-type ComponentArray from BackendComponent
 */
final class MainBackendComponent implements CompoundComponent, Htmlable, LivewireComponent
{
    use HasContent,
        HasPath,
        HasSettings,
        IsBackendComponent,
        IsLivewireComponent,
        IsThemeable;

    public function __construct(
        private string|BackedEnum $name,
        ThemeManager $themeManager = new DefaultThemeManager
    ) {
        $this->themeManager = $themeManager;
    }

    public function getAttributeBag(): AttributeBag
    {
        return new DefaultAttributeBag(
            attributes: $this->getAttributes(),
            content: $this->processContent(),
            themes: $this->compileTheme(),
            path: $this->getComponentPath(),
            settings: $this->getSettings(),
            isLivewire: $this->isLivewire(),
            livewireKey: $this->getLivewireKey(),
            livewireParams: $this->getLivewireParams(),
        );
    }

    /**
     * @return ComponentArray
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'component' => self::class,
            'attributes' => $this->getAttributes(),
            'contents' => $this->processContent()->toArray(),
            'theme' => [
                'manager' => \get_class($this->themeManager),
                'themes' => $this->getThemes(),
                'path' => $this->themeManager->getDefaultPath(),
                'realPath' => $this->themeManager->getThemePath(),
            ],
            'path' => $this->getPathOnly(),
            'settings' => $this->getSettings(),
        ];
    }

    public function toHtml(): string
    {
        /**
         * PHPStan bug
         * https://github.com/larastan/larastan/issues/2213
         *
         * @phpstan-ignore argument.type
         */
        return \view($this->getContext().'_utilities.resolve-components')
            ->with('component', $this)
            ->with('namespace', $this->getContext())
            ->render();

    }
}
