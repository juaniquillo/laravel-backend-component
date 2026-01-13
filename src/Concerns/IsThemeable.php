<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Concerns;

use Juaniquillo\BackendComponents\Contracts\ThemeManager;

trait IsThemeable
{
    /**
     * @var array<string, string|array<string|int, string>>
     */
    private array $themes = [];

    private ThemeManager $themeManager;

    public function setThemeManager(ThemeManager $themeManager): static
    {
        $this->themeManager = $themeManager;

        return $this;
    }

    /**
     * @return array<string, string|array<string|int, string>>
     */
    public function getThemes(): array
    {
        return $this->themes;
    }

    /**
     * @return string|array<string|int, string>|null
     */
    public function getTheme(string $name): string|array|null
    {
        return $this->getThemes()[$name] ?? null;
    }

    public function getThemeManager(): ThemeManager
    {
        return $this->themeManager;
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

    public function compileTheme(): ?string
    {
        $themes = $this->getThemes();

        if (! count($themes)) {
            return null;
        }

        return $this->getThemeManager()
            ->processThemes(themes: $themes);
    }
}
