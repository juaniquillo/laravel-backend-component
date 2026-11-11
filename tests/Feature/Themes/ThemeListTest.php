<?php

declare(strict_types=1);

namespace Feature\Themes;

use Exception;
use Juaniquillo\BaseBackendComponent\Utils\ThemeList;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ThemeListTest extends TestCase
{
    #[Test]
    public function all_themes_are_valid_arrays()
    {
        $list = ThemeList::make(__DIR__.'/../../../resources/views/_themes/tailwind');

        $this->assertIsArray($list->scanFiles());
    }

    #[Test]
    public function all_theme_sub_folders_are_ignored()
    {
        $list = ThemeList::make(__DIR__.'/../../Themes/');

        $list = $list->scanFiles();

        $this->assertIsArray($list);
        $this->assertArrayNotHasKey('ignored', $list, 'The "ignored" theme should not be present in the list');

    }

    #[Test]
    public function if_the_theme_path_does_not_exist_an_exception_is_thrown()
    {
        $list = new ThemeList(
            path: 'non/existent/path'
        );

        $this->expectException(Exception::class);

        $list->scanFiles();

    }

    #[Test]
    public function if_the_file_does_not_exist_an_exception_is_thrown()
    {
        $list = new ThemeList(__DIR__.'/../../Themes/');

        $this->expectException(Exception::class);

        $list->getThemes(['non_existent_file']);

    }
}
