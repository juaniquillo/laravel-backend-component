<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Concerns;

use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;

trait isFactory
{
    /**
     * @param array{
     *  name:  int|string,
     *  component: class-string<BackendComponent|CompoundComponent>,
     *  attributes: array<string, int|string|null>,
     *  contents?: array<string,array<string, int|string>|int|string>,
     *  theme?: array{
     *   manager: class-string<ThemeManager>,
     *   themes: array<string, array<int|string, string>|string>,
     *   path: string,
     *   realPath: string,
     *  },
     *  path?: string,
     *  settings?: array<string, bool|string>,
     *  isLivewire?: bool,
     *  livewireKey?: string|null,
     *  livewireParams?: array<string, mixed>
     * } $componentArray
     */
    public static function fromArray(array $componentArray): BackendComponent|CompoundComponent
    {
        return (new self)->resolveComponent($componentArray);
    }

    /**
     * @param array{
     *  name:  int|string,
     *  component: class-string<BackendComponent|CompoundComponent>,
     *  attributes: array<string, int|string|null>,
     *  contents?: array<string,array<string, int|string>|int|string>,
     *  theme?: array{
     *   manager: class-string<ThemeManager>,
     *   themes: array<string, array<int|string, string>|string>,
     *   path: string,
     *   realPath: string,
     *  },
     *  path?: string,
     *  settings?: array<string, bool|string>,
     *  isLivewire?: bool,
     *  livewireKey?: string|null,
     *  livewireParams?: array<string, mixed>
     * } $componentArray
     */
    private function resolveComponent(array $componentArray): BackendComponent|CompoundComponent
    {
        /** @var CompoundComponent $component */
        $component = $this->initComponent($componentArray);

        $component->setAttributes($componentArray['attributes']);

        $contents = $componentArray['contents'] ?? null;
        if ($contents) {
            $component->setContents(
                $this->resolveArrayContents($contents)
            );
        }

        if ($componentArray['theme'] ?? null) {

            $themeManager = $this->resolveThemeManager(
                $componentArray['theme']['manager'],
                $componentArray['theme']['path']
            );

            $component->setThemeManager($themeManager);
            $component->setThemes($componentArray['theme']['themes']);
        }

        if (($componentArray['settings'] ?? null) && count($componentArray['settings'])) {
            $component->setSettings($componentArray['settings']);
        }

        if ($componentArray['path'] ?? null) {
            $component->setPath($componentArray['path']);
        }

        if (($componentArray['isLivewire'] ?? null) && $componentArray['isLivewire']) {
            $component->setLivewire($componentArray['isLivewire']);

            if ($componentArray['livewireKey'] ?? null) {
                $component->setLivewireKey($componentArray['livewireKey']);
            }

            if (($componentArray['livewireParams'] ?? null) && count($componentArray['livewireParams'])) {
                $component->setLivewireParams($componentArray['livewireParams']);
            }
        }

        return $component;
    }

    /**
     * @param array{
     *  name:  int|string,
     *  component: class-string<BackendComponent|CompoundComponent>,
     * } $componentArray
     */
    public function initComponent(array $componentArray): BackendComponent|CompoundComponent
    {
        $componentClass = $componentArray['component'];

        return new $componentClass($componentArray['name']);
    }

    /**
     * @param  array<string, array<string, int|string>|int|string>  $contentsArray
     * @return array<string|int, string|int|CompoundComponent|BackendComponent>
     */
    private function resolveArrayContents(array $contentsArray): array
    {
        $contents = [];

        foreach ($contentsArray as $name => $content) {
            $contents[$name] = is_array($content)
                /**
                 * Don't know how to describe concurrency
                 * here with phpstan
                 *
                 * @phpstan-ignore argument.type
                 */
                ? $this->resolveComponent($content)
                : $content;
        }

        return $contents;
    }

    /**
     * @param  class-string<ThemeManager>  $managerClass
     */
    public function resolveThemeManager(string $managerClass, string $path): ThemeManager
    {
        $manager = new $managerClass;
        $manager->setDefaultPath($path);

        return $manager;
    }
}
