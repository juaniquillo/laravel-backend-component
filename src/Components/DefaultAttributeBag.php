<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Components;

use Juaniquillo\BackendComponents\Contracts\AttributeBag;
use Juaniquillo\BackendComponents\Contracts\ContentsComponent;

readonly class DefaultAttributeBag implements AttributeBag
{
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
        $attrs = $this->attributes;

        $mergedClasses = $this->mergeClasses();

        if ($mergedClasses) {
            $attrs['class'] = $mergedClasses;
        }

        return $attrs;
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

    private function mergeClasses(): string
    {
        $class = $this->attributes['class'] ?? null ? $this->attributes['class'].' ' : null;

        return \trim($class.$this->themes);
    }
}
