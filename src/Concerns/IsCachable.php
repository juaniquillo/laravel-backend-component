<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Concerns;

use Juaniquillo\BackendComponents\Contracts\LivewireComponent;
use Juaniquillo\BaseBackendComponent\Cache\FileCache;
use Psr\SimpleCache\CacheInterface;

trait IsCachable
{
    private ?string $cacheDirectory = null;

    private bool $cacheEnabled = true;

    private ?CacheInterface $cacheStore = null;

    public function getCachedHtml(): string
    {
        if (! $this->cacheEnabled || ($this instanceof LivewireComponent && $this->isLivewire())) {
            return $this->toHtml();
        }

        $cache = $this->getCacheStore();
        $key = $this->getCacheKey();
        $cached = $cache->get($key);

        if (\is_string($cached)) {
            return $cached;
        }

        $html = $this->toHtml();
        $cache->set($key, $html);

        return $html;
    }

    public function clearCache(): void
    {
        $this->getCacheStore()->delete($this->getCacheKey());
    }

    public function getCacheKey(): string
    {
        $json = \json_encode($this->toArray());

        return \md5($json !== false ? $json : '');
    }

    public function setCacheDirectory(string $path): static
    {
        $this->cacheDirectory = $path;
        $this->cacheStore = null;

        return $this;
    }

    /**
     * Inject a custom PSR-16 cache store (e.g. `Cache::store('redis')`).
     *
     * Last call wins: calling `setCacheDirectory()` afterwards resets the
     * store back to `FileCache`. The store is not serialized by `toArray()`,
     * reattach it after `ComponentDeserializer::fromArray()`.
     */
    public function setCacheStore(CacheInterface $cacheStore): static
    {
        $this->cacheStore = $cacheStore;

        return $this;
    }

    public function getCacheDirectory(): ?string
    {
        return $this->cacheDirectory;
    }

    public function disableCache(): static
    {
        $this->cacheEnabled = false;

        return $this;
    }

    public function enableCache(): static
    {
        $this->cacheEnabled = true;

        return $this;
    }

    public function isCacheEnabled(): bool
    {
        return $this->cacheEnabled;
    }

    public function getCacheStore(): CacheInterface
    {
        if ($this->cacheStore === null) {
            $dir = $this->cacheDirectory ?? \base_path('cache/backend-components');
            $this->cacheStore = new FileCache($dir);
        }

        return $this->cacheStore;
    }
}
