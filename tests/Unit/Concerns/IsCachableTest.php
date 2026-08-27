<?php

declare(strict_types=1);

namespace Tests\Unit\Concerns;

use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Components\CachedBackendComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IsCachableTest extends TestCase
{
    private string $cacheDir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cacheDir = sys_get_temp_dir().'/backend-components-test-'.\uniqid();
    }

    protected function tearDown(): void
    {
        $this->deleteDirectory($this->cacheDir);

        parent::tearDown();
    }

    #[Test]
    public function get_cached_html_returns_html(): void
    {
        $component = new CachedBackendComponent(ComponentEnum::DIV);
        $component->setCacheDirectory($this->cacheDir);

        $html = $component->getCachedHtml();

        $this->assertIsString($html);
        $this->assertNotEmpty($html);
    }

    #[Test]
    public function get_cached_html_returns_cached_on_second_call(): void
    {
        $component = new CachedBackendComponent(ComponentEnum::DIV);
        $component->setCacheDirectory($this->cacheDir);

        $html1 = $component->getCachedHtml();
        $html2 = $component->getCachedHtml();

        $this->assertEquals($html1, $html2);

        $cacheFile = $this->cacheDir.'/'.$component->getCacheKey().'.cache';
        $this->assertFileExists($cacheFile);
    }

    #[Test]
    public function get_cached_html_returns_fresh_after_content_change(): void
    {
        $component1 = new CachedBackendComponent(ComponentEnum::DIV);
        $component1->setCacheDirectory($this->cacheDir);
        $component1->setContent('Hello');

        $key1 = $component1->getCacheKey();

        $component2 = new CachedBackendComponent(ComponentEnum::DIV);
        $component2->setCacheDirectory($this->cacheDir);
        $component2->setContent('World');

        $key2 = $component2->getCacheKey();

        $this->assertNotEquals($key1, $key2);
    }

    #[Test]
    public function clear_cache_removes_cached_file(): void
    {
        $component = new CachedBackendComponent(ComponentEnum::DIV);
        $component->setCacheDirectory($this->cacheDir);

        $component->getCachedHtml();

        $cacheFile = $this->cacheDir.'/'.$component->getCacheKey().'.cache';
        $this->assertFileExists($cacheFile);

        $component->clearCache();

        $this->assertFileDoesNotExist($cacheFile);
    }

    #[Test]
    public function get_cache_key_returns_stable_hash(): void
    {
        $component1 = new CachedBackendComponent(ComponentEnum::DIV);
        $component1->setCacheDirectory($this->cacheDir);
        $component1->setAttribute('class', 'test');
        $component1->setContent('Hello');

        $component2 = new CachedBackendComponent(ComponentEnum::DIV);
        $component2->setCacheDirectory($this->cacheDir);
        $component2->setAttribute('class', 'test');
        $component2->setContent('Hello');

        $this->assertEquals($component1->getCacheKey(), $component2->getCacheKey());
    }

    #[Test]
    public function get_cache_key_changes_when_attributes_change(): void
    {
        $component1 = new CachedBackendComponent(ComponentEnum::DIV);
        $component1->setCacheDirectory($this->cacheDir);
        $component1->setAttribute('class', 'foo');

        $component2 = new CachedBackendComponent(ComponentEnum::DIV);
        $component2->setCacheDirectory($this->cacheDir);
        $component2->setAttribute('class', 'bar');

        $this->assertNotEquals($component1->getCacheKey(), $component2->getCacheKey());
    }

    #[Test]
    public function get_cache_key_changes_when_themes_change(): void
    {
        $component1 = new CachedBackendComponent(ComponentEnum::BUTTON);
        $component1->setCacheDirectory($this->cacheDir);
        $component1->setTheme('action', 'success');

        $component2 = new CachedBackendComponent(ComponentEnum::BUTTON);
        $component2->setCacheDirectory($this->cacheDir);
        $component2->setTheme('action', 'error');

        $this->assertNotEquals($component1->getCacheKey(), $component2->getCacheKey());
    }

    #[Test]
    public function get_cache_key_changes_when_content_changes(): void
    {
        $component1 = new CachedBackendComponent(ComponentEnum::DIV);
        $component1->setCacheDirectory($this->cacheDir);
        $component1->setContent('A');

        $component2 = new CachedBackendComponent(ComponentEnum::DIV);
        $component2->setCacheDirectory($this->cacheDir);
        $component2->setContent('B');

        $this->assertNotEquals($component1->getCacheKey(), $component2->getCacheKey());
    }

    #[Test]
    public function disable_cache_always_renders_fresh(): void
    {
        $component = new CachedBackendComponent(ComponentEnum::DIV);
        $component->setCacheDirectory($this->cacheDir);
        $component->disableCache();

        $html1 = $component->getCachedHtml();
        $html2 = $component->getCachedHtml();

        $this->assertEquals($html1, $html2);

        $cacheFile = $this->cacheDir.'/'.$component->getCacheKey().'.cache';
        $this->assertFileDoesNotExist($cacheFile);
    }

    #[Test]
    public function enable_cache_after_disable(): void
    {
        $component = new CachedBackendComponent(ComponentEnum::DIV);
        $component->setCacheDirectory($this->cacheDir);

        $component->disableCache();
        $component->getCachedHtml();

        $cacheFile = $this->cacheDir.'/'.$component->getCacheKey().'.cache';
        $this->assertFileDoesNotExist($cacheFile);

        $component->enableCache();
        $component->getCachedHtml();

        $this->assertFileExists($cacheFile);
    }

    #[Test]
    public function set_cache_directory_uses_custom_path(): void
    {
        $customDir = $this->cacheDir.'/custom';
        $component = new CachedBackendComponent(ComponentEnum::DIV);
        $component->setCacheDirectory($customDir);

        $this->assertEquals($customDir, $component->getCacheDirectory());

        $component->getCachedHtml();

        $this->assertDirectoryExists($customDir);
    }

    #[Test]
    public function livewire_component_skips_cache(): void
    {
        $component = new CachedBackendComponent(ComponentEnum::DIV);
        $component->setCacheDirectory($this->cacheDir);
        $component->setLivewire();
        $component->setLivewireKey('test-key');

        $component->getCachedHtml();

        $cacheFile = $this->cacheDir.'/'.$component->getCacheKey().'.cache';
        $this->assertFileDoesNotExist($cacheFile);
    }

    #[Test]
    public function nested_component_cache_captures_tree(): void
    {
        $parent = new CachedBackendComponent(ComponentEnum::DIV);
        $parent->setCacheDirectory($this->cacheDir);

        $child = ComponentBuilder::make(ComponentEnum::SPAN);
        $child->setContent('Child content');
        $parent->setContent($child);

        $keyWithChild = $parent->getCacheKey();

        $parent2 = new CachedBackendComponent(ComponentEnum::DIV);
        $parent2->setCacheDirectory($this->cacheDir);

        $keyWithoutChild = $parent2->getCacheKey();

        $this->assertNotEquals($keyWithChild, $keyWithoutChild);
    }

    #[Test]
    public function is_cache_enabled_returns_default_state(): void
    {
        $component = new CachedBackendComponent(ComponentEnum::DIV);

        $this->assertTrue($component->isCacheEnabled());
    }

    #[Test]
    public function get_cache_directory_returns_null_by_default(): void
    {
        $component = new CachedBackendComponent(ComponentEnum::DIV);

        $this->assertNull($component->getCacheDirectory());
    }

    #[Test]
    public function cache_key_is_string(): void
    {
        $component = new CachedBackendComponent(ComponentEnum::DIV);
        $key = $component->getCacheKey();

        $this->assertIsString($key);
        $this->assertMatchesRegularExpression('/^[a-f0-9]+$/', $key);
    }

    private function deleteDirectory(string $dir): void
    {
        if (! \is_dir($dir)) {
            return;
        }

        $files = \glob($dir.'/*');

        if ($files !== false) {
            foreach ($files as $file) {
                if (\is_dir($file)) {
                    $this->deleteDirectory($file);
                } else {
                    \unlink($file);
                }
            }
        }

        \rmdir($dir);
    }
}
