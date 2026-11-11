<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Components;

use Juaniquillo\BaseBackendComponent\Concerns\MergesAttributeClasses;
use Juaniquillo\BaseBackendComponent\Contracts\AttributeBag;
use Juaniquillo\BaseBackendComponent\Contracts\ContentsComponent;

readonly class DefaultAttributeBag implements AttributeBag
{
    use MergesAttributeClasses;

    public function __construct(
        /** @var array<string, int|string|null> $attributes */
        private array $attributes,
        public readonly ?ContentsComponent $content = null,
        public readonly ?string $themes = null,
        public readonly ?string $path = null,
        /** @var array<string, bool|string> $settings */
        public readonly array $settings = [],
        public readonly bool $isLivewire = false,
        public readonly ?string $livewireKey = null,
        /** @var array<string, mixed> $livewireParams */
        public readonly array $livewireParams = [],
        /** @var array<string, mixed> $props */
        public readonly array $props = [],
    ) {}

    /** @return  array<string, int|string|null> */
    public function getAttributes(): array
    {
        return $this->mergeThemeClasses($this->attributes, $this->themes);
    }

    /** @return array<string, mixed> */
    public function getProps(): array
    {
        return $this->props;
    }

    /** @return array<string, mixed> */
    public function getAttributesAndProps(): array
    {
        return array_merge($this->getAttributes(), $this->props);
    }
}
