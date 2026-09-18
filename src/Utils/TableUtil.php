<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Utils;

use BackedEnum;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\MainBackendComponent;
use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;

use function Juaniquillo\BackendComponents\isCellBag;
use function Juaniquillo\BackendComponents\isComponent;

final class TableUtil
{
    private ?string $caption = null;

    /**
     * @var array<string, string|array<string|int, string>>
     */
    private array $tableThemes = [
        'table' => 'table',
    ];

    /**
     * @var array<string, string|array<string|int, string>>
     */
    private array $thThemes = [
        'table' => [
            'th',
            'th-dark',
        ],
    ];

    /**
     * @var array<string, string|array<string|int, string>>
     */
    private array $trThemes = [
        'table' => [],
    ];

    /**
     * @var array<string, string|array<string|int, string>>
     */
    private array $tdThemes = [
        'table' => [
            'td',
            'td-dark',
        ],
    ];

    /**
     * @var array<string, string|array<string|int, string>>
     */
    private array $captionThemes = [
        'table' => [],
    ];

    /**
     * @param  array<string|int, string|BackendComponent|CellBag|array{
     *   content: string|int|BackendComponent,
     *   theme?: array<string, string|array<string|int, string>>,
     *   attributes?: array<string, int|string|null>
     * }>  $head
     * @param  array<string|int, array<string|int, string|BackendComponent|CellBag|array{
     *   content: string|int|BackendComponent,
     *   theme?: array<string, string|array<string|int, string>>,
     *   attributes?: array<string, string|int|null>
     * }>>  $body
     */
    public function __construct(
        private array $head,
        private array $body,
        private ThemeManager $themeManager = new DefaultThemeManager
    ) {}

    /**
     * @param  array<string|int, string|BackendComponent|CellBag|array{
     *   content: string|int|BackendComponent,
     *   theme?: array<string, string|array<string|int, string>>,
     *   attributes?: array<string, string|int|null>
     * }>  $head
     * @param  array<string|int, array<string|int, string|BackendComponent|CellBag|array{
     *   content: string|int|BackendComponent,
     *   theme?: array<string, string|array<string|int, string>>,
     *   attributes?: array<string, string|int|null>
     * }>>  $body
     */
    public static function make(array $head, array $body, ThemeManager $themeManager = new DefaultThemeManager): static
    {
        return new self($head, $body, $themeManager);
    }

    public function setCaption(string $caption): self
    {
        $this->caption = $caption;

        return $this;

    }

    /**
     * @param  array<string, string|array<string|int, string>>  $themes
     */
    public function setTableThemes(array $themes): static
    {
        $this->tableThemes = $themes;

        return $this;
    }

    /**
     * @param  array<string, string|array<string|int, string>>  $themes
     */
    public function setThThemes(array $themes): static
    {
        $this->thThemes = $themes;

        return $this;
    }

    /**
     * @param  array<string, string|array<string|int, string>>  $themes
     */
    public function setTrThemes(array $themes): static
    {
        $this->trThemes = $themes;

        return $this;
    }

    /**
     * @param  array<string, string|array<string|int, string>>  $themes
     */
    public function setTdThemes(array $themes): static
    {
        $this->tdThemes = $themes;

        return $this;
    }

    /**
     * @param  array<string, string|array<string|int, string>>  $themes
     */
    public function setCaptionThemes(array $themes): static
    {
        $this->captionThemes = $themes;

        return $this;

    }

    public function getComponent(): BackendComponent
    {

        $theme = $this->tableThemes;

        $contents = [];

        if ($this->caption !== null) {
            $contents[] = $this->composeComponent(ComponentEnum::CAPTION, $this->caption, $this->captionThemes);
        }

        if (\count($this->head)) {
            $contents[] = $this->head();
        }

        $contents[] = $this->body();

        return $this->composeComponent(ComponentEnum::TABLE, $contents, $theme);
    }

    private function head(): BackendComponent
    {
        $columns = [];

        $themeTh = $this->thThemes;

        foreach ($this->head as $value) {

            $columns[] = $this->composeComponent(
                name: ComponentEnum::TH,
                contents: $this->resolveContent($value),
                theme: $this->resolveTheme($themeTh, $value),
                attributes: $this->resolveAttributes($value),
            );

        }

        return $this->composeComponent(ComponentEnum::THEAD, [
            $this->composeComponent(ComponentEnum::TR, $columns),
        ]);

    }

    private function body(): BackendComponent
    {
        $rows = [];

        $theme = $this->trThemes;

        foreach ($this->body as $row) {
            $rows[] = $this->composeComponent(
                ComponentEnum::TR,
                $this->rows($row),
                $theme
            );
        }

        return $this->composeComponent(ComponentEnum::TBODY, $rows);
    }

    /**
     * @param  array<string|int, string|BackendComponent|CellBag|array{
     *   content: string|int|BackendComponent,
     *   theme?: array<string, string|array<string|int, string>>,
     *   attributes?: array<string, string|int|null>
     * }>  $rows
     * @return array<int, BackendComponent>
     */
    private function rows(array $rows): array
    {
        $cells = [];

        $themeTd = $this->tdThemes;

        foreach ($rows as $value) {

            $cells[] = $this->composeComponent(
                ComponentEnum::TD,
                contents: $this->resolveContent($value),
                theme: $this->resolveTheme($themeTd, $value),
                attributes: $this->resolveAttributes($value),
            );

        }

        return $cells;
    }

    /**
     * @param string|BackendComponent|CellBag|array{
     *   content: string|int|BackendComponent,
     *   theme?: array<string, string|array<string|int, string>>,
     *   attributes?: array<string, string|int|null>
     * } $content
     */
    private function resolveContent(array|string|CellBag|BackendComponent $content): string|int|BackendComponent
    {
        if (isCellBag($content)) {
            return $content->content;
        }

        if (\is_array($content)) {
            return $content['content'];
        }

        if (isComponent($content)) {
            return $content;
        }

        return $content;

    }

    /**
     * @param string|BackendComponent|CellBag|array{
     *   content: string|int|BackendComponent,
     *   theme?: array<string, string|array<string|int, string>>,
     *   attributes?: array<string, string|int|null>
     * } $content
     * @param  array<string, string|array<string|int, string>>  $theme
     * @return array<string, string|array<string|int, string>>
     */
    private function resolveTheme(array $theme, array|string|CellBag|BackendComponent $content): array
    {
        if (isCellBag($content) && $content->theme) {
            return $content->theme;
        }

        if (\is_array($content) && isset($content['theme'])) {
            return $content['theme'];
        }

        return $theme;

    }

    /**
     * @param string|BackendComponent|CellBag|array{
     *   content: string|int|BackendComponent,
     *   theme?: array<string, string|array<string|int, string>>,
     *   attributes?: array<string, string|int|null>
     * } $content
     * @return array<string, int|string|null>
     */
    private function resolveAttributes(array|string|CellBag|BackendComponent $content): array
    {
        if (isCellBag($content) && $content->attributes) {
            return $content->attributes;
        }

        if (\is_array($content) && isset($content['attributes'])) {
            return $content['attributes'];
        }

        return [];

    }

    /**
     * @param  int|string|BackendComponent|array<string|int, int|string|BackendComponent>  $contents
     * @param  array<string, string|array<string|int, string>>|null  $theme
     * @param  array<string, int|string|null>  $attributes
     */
    public function composeComponent(BackedEnum $name, int|array|string|BackendComponent $contents, ?array $theme = null, ?array $attributes = null): BackendComponent
    {
        $contents = \is_array($contents) ? $contents : [$contents];

        $component = (new MainBackendComponent($name, $this->themeManager))
            ->setContents($contents);

        if ($theme) {
            $component->setThemes($theme);
        }

        if ($attributes) {
            $component->setAttributes($attributes);
        }

        return $component;
    }
}
