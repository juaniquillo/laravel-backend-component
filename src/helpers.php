<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents {

    use BackedEnum;
    use Juaniquillo\BackendComponents\Cache\DefaultCache;
    use Juaniquillo\BackendComponents\Contracts\BackendComponent;
    use Juaniquillo\BackendComponents\Contracts\ThemeManager;
    use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;
    use Juaniquillo\BackendComponents\Themes\LocalThemeManager;
    use Juaniquillo\BackendComponents\Utils\CellBag;

    function backendComponentNamespace(): string
    {
        return BackendComponentsServiceProvider::namespace().'::';
    }

    function makeBackendComponent(string|BackedEnum $name, ThemeManager $manager = new DefaultThemeManager): MainBackendComponent
    {
        return new MainBackendComponent(name: $name, themeManager: $manager);
    }

    /**
     * @param  array<string, string|array<string, string>>  $themes
     */
    function processThemes(array $themes, ThemeManager $manager = new DefaultThemeManager): ?string
    {
        return $manager->processThemes(themes: $themes);
    }

    /**
     * @param  array<string, string|array<string, string>>  $themes
     */
    function processLocalThemes(array $themes): ?string
    {
        $manager = new LocalThemeManager;

        return $manager->processThemes(themes: $themes);
    }

    /**
     * @return DefaultCache<string|null>
     */
    function cache(string $name): DefaultCache
    {
        /**
         * @var array<string, DefaultCache<string|null>>
         */
        static $cache = [];

        if (! isset($cache[$name])) {
            $cache[$name] = new DefaultCache;
        }

        return $cache[$name];

    }

    /** @phpstan-assert-if-true BackendComponent $component */
    function isComponent(mixed $component): bool
    {
        return $component instanceof BackendComponent;
    }

    /** @phpstan-assert-if-true BackedEnum $enum */
    function isBackedEnum(mixed $enum): bool
    {
        return $enum instanceof BackedEnum;
    }

    /** @phpstan-assert-if-true CellBag $bag */
    function isCellBag(mixed $bag): bool
    {
        return $bag instanceof CellBag;
    }
}
