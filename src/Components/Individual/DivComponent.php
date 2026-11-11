<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Components\Individual;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\View\ComponentAttributeBag;
use Juaniquillo\BackendComponents\Components\DefaultAttributeBag;
use Juaniquillo\BackendComponents\Concerns\HasContent;
use Juaniquillo\BackendComponents\Concerns\IsBackendComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;
use Juaniquillo\BaseBackendComponent\Concerns\IsThemeable;
use Juaniquillo\BaseBackendComponent\Contracts\AttributeBag;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\ContentsComponent;
use Juaniquillo\BaseBackendComponent\Contracts\IndividualComponent;
use Juaniquillo\BaseBackendComponent\Contracts\ThemeComponent;
use Juaniquillo\BaseBackendComponent\Contracts\ThemeManager;

use function Juaniquillo\BackendComponents\backendComponentNamespace;

final class DivComponent implements BackendComponent, ContentsComponent, Htmlable, IndividualComponent, ThemeComponent
{
    use HasContent,
        IsBackendComponent,
        IsThemeable;

    public function __construct(
        ThemeManager $themeManager = new DefaultThemeManager
    ) {
        $this->themeManager = $themeManager;
    }

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
                'manager' => \get_class($this->themeManager),
                /** @var array<string, string|array<string|int, string>> */
                'themes' => $this->getThemes(),
                'path' => $this->themeManager->getDefaultPath(),
                'realPath' => $this->themeManager->getThemePath(),
            ],
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
        return view($this->getViewPath())
            ->with('attributes', new ComponentAttributeBag($this->getAttributeBag()->getAttributes()))
            ->with('slot', $this->processContent())
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

    /**
     * Unlike the HasPath trait, getPathOnly() already includes the
     * "components" directory, so the component path is the view path.
     */
    public function getViewPath(): string
    {
        return $this->getComponentPath();
    }

    public function getPathOnly(): string
    {
        return 'components.'
            .$this->getName();
    }
}
