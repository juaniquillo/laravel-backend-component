<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Components\Individual;

use Illuminate\Contracts\Support\Htmlable;
use Juaniquillo\BackendComponents\Components\DefaultAttributeBag;
use Juaniquillo\BackendComponents\Concerns\HasContent;
use Juaniquillo\BackendComponents\Concerns\IsBackendComponent;
use Juaniquillo\BackendComponents\Concerns\IsThemeable;
use Juaniquillo\BackendComponents\Contracts\AttributeBag;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\ContentsComponent;
use Juaniquillo\BackendComponents\Contracts\IndividualComponent;
use Juaniquillo\BackendComponents\Contracts\ThemeComponent;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;

use function Juaniquillo\BackendComponents\backendComponentNamespace;

final class DivComponent implements BackendComponent, ContentsComponent, Htmlable, IndividualComponent, ThemeComponent
{
    use HasContent,
        IsBackendComponent,
        IsThemeable;

    public function __construct(
        private ThemeManager $themeManager = new DefaultThemeManager
    ) {}

    public static function make(ThemeManager $themeManager = new DefaultThemeManager): static
    {
        return new self($themeManager);
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'component' => self::class,
            'attributes' => $this->getAttributes(),
            'contents' => $this->processContent()->toArray(),
            'theme' => [
                'manager' => get_class($this->themeManager),
                /** @var array<string, string|array<string|int, string>> */
                'themes' => $this->getThemes(),
                'path' => $this->themeManager->getDefaultPath(),
                'realPath' => $this->themeManager->getThemePath(),
            ],
        ];
    }

    public function toHtml()
    {
        /**
         * PHPStan bug
         * https://github.com/larastan/larastan/issues/2213
         *
         * @phpstan-ignore argument.type
         */
        return view($this->getComponentPath())
            ->with('attrs', $this->getAttributeBag())
            ->render();
    }

    public function getAttributeBag(): AttributeBag
    {
        return new DefaultAttributeBag(
            attributes: $this->getAttributes(),
            content: $this->processContent(),
            themes: $this->compileTheme(),
        );
    }

    public function getName(): string
    {
        return ComponentEnum::DIV->value;
    }

    public function getComponentPath(): string
    {
        return backendComponentNamespace()
            .$this->getPathOnly();
    }

    public function getPathOnly(): string
    {
        return 'components.'
            .$this->getName();
    }
}
