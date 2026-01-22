<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Utils;

final class ThemeList
{
    private string $path = __DIR__.'/../../resources/views/_themes/tailwind';

    public function __construct(?string $path = null)
    {
        if ($path) {
            $this->path = $path;
        }

    }

    public static function make(?string $path = null): static
    {
        return new self($path);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function scanFiles(): array
    {
        $path = $this->path;
        $realpath = realpath($path);

        if (! $realpath) {
            throw new \Exception("The path ({$path}) is incorrect", 500);
        }

        /** @var array<int, string> $files */
        $files = scandir($realpath);

        $themes = $this->getThemes($files);

        /**
         * Consider caching if not
         * using static files
         */

        return $themes;
    }

    /**
     * @param  array<int, string>  $files
     * @return array<string, array<string, mixed>>
     */
    public function getThemes(array $files, ?string $subFile = null): array
    {
        $themes = [];

        foreach ($files as $file) {

            if ($file === '.' || $file === '..') {
                continue;
            }

            $path = $this->path;

            $realPath = realpath($path.'/'.$subFile.'/'.$file);

            if (! $realPath) {
                throw new \Exception('The theme file '.$file.' does not exist', 500);
            }

            if (is_dir($realPath)) {
                /**
                 * @todo recursively get themes from subdirectories
                 */
                continue;
            }

            // Get the theme name from the file name
            $themeName = str_replace('.blade', '', pathinfo($file, PATHINFO_FILENAME));

            $themeArray = require $realPath;

            $themes[$themeName] = [
                'themes' => $themeArray,
                'file' => $file,
                'path' => $realPath,
            ];
        }

        return $themes;
    }
}
