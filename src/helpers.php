<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents {

    use BackedEnum;
    use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;
    use Juaniquillo\BackendComponents\Themes\LocalThemeManager;
    use Juaniquillo\BackendComponents\Utils\CellBag;
    use Juaniquillo\BaseBackendComponent\Contracts\ThemeManager;

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

    /** @phpstan-assert-if-true CellBag $bag */
    function isCellBag(mixed $bag): bool
    {
        return $bag instanceof CellBag;
    }
}
