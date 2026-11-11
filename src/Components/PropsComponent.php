<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Components;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\View\ComponentAttributeBag;
use Juaniquillo\BackendComponents\Concerns\HasContent;
use Juaniquillo\BackendComponents\Concerns\HasPath;
use Juaniquillo\BackendComponents\Concerns\IsBackendComponent;
use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;
use Juaniquillo\BaseBackendComponent\Concerns\HasProps;
use Juaniquillo\BaseBackendComponent\Concerns\HasSettings;
use Juaniquillo\BaseBackendComponent\Concerns\IsThemeable;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\ContentComponent;
use Juaniquillo\BaseBackendComponent\Contracts\PathComponent;
use Juaniquillo\BaseBackendComponent\Contracts\PropsComponent as PropsContract;
use Juaniquillo\BaseBackendComponent\Contracts\SettingsComponent;
use Juaniquillo\BaseBackendComponent\Contracts\ThemeComponent;
use Juaniquillo\BaseBackendComponent\Contracts\ThemeManager;

/**
 * @phpstan-import-type ComponentArray from BackendComponent
 */
final class PropsComponent implements BackendComponent, ContentComponent, Htmlable, PathComponent, PropsContract, SettingsComponent, ThemeComponent
{
    use HasContent;
    use HasPath;
    use HasProps;
    use HasSettings;
    use IsBackendComponent;
    use IsThemeable;

    public function __construct(
        private string|BackedEnum $name,
        ThemeManager $themeManager = new DefaultThemeManager
    ) {
        $this->themeManager = $themeManager;
    }

    public function isLivewire(): bool
    {
        return false;
    }

    public function getAttributeBag(): DefaultAttributeBag
    {
        return new DefaultAttributeBag(
            attributes: $this->getAttributes(),
            content: $this->processContent(),
            themes: $this->compileTheme(),
            path: $this->getComponentPath(),
            settings: $this->getSettings(),
            props: $this->getProps(),
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
        $attributes = $this->getAttributeBag()->getAttributesAndProps();
        $attributeBag = new ComponentAttributeBag($attributes);

        /**
         * PHPStan bug
         * https://github.com/larastan/larastan/issues/2213
         *
         * @phpstan-ignore argument.type
         */
        return \view($this->getContext().'_utilities.resolve-third-party-component')
            ->with('path', $this->getComponentPath())
            ->with('attributes', $attributeBag)
            ->with('content', $this->processContent())
            ->render();
    }
}
