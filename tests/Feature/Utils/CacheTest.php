<?php

declare(strict_types=1);

namespace Feature\Utils;

use Juaniquillo\BackendComponents\Contracts\Cache as ContractsCache;
use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Juaniquillo\BackendComponents\cache;

class CacheTest extends TestCase
{
    #[Test]
    public function a_cache_can_be_created_using_the_cache_helper()
    {
        $cache = cache(DefaultThemeManager::THEME_CACHE_NAME);

        $this->assertInstanceOf(ContractsCache::class, $cache);
    }

    #[Test]
    public function a_value_can_be_added_to_the_cache()
    {
        $cache = cache(DefaultThemeManager::THEME_CACHE_NAME);

        $key = 'cache-key';
        $value = 'cache-value';

        $cache->set($key, $value);

        $this->assertEquals($value, $cache->get($key));

    }

    #[Test]
    public function a_value_can_be_removed_from_the_cache()
    {
        $cache = cache(DefaultThemeManager::THEME_CACHE_NAME);

        $key = 'cache-key';
        $value = 'cache-value';

        $cache->set($key, $value);

        $this->assertEquals($value, $cache->get($key));

        $cache->delete($key);

        $this->assertNull($cache->get($key));

    }
}
