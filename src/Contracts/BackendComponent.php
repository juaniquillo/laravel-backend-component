<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Contracts;

interface BackendComponent
{
    /**
     * @return array<string, int|string|null>
     */
    public function getAttributes(): array;

    public function getAttribute(string $name): int|string|null;

    public function getAttributeBag(): AttributeBag;

    public function setAttribute(string $name, int|string|null $content): static;

    /**
     * @param  array<string, int|string|null>  $attributes
     */
    public function setAttributes(array $attributes): static;

    public function __toString(): string;

    /**
     * @return array<string, array<string, mixed>|bool|int|string|null>
     */
    public function toArray(): array;
}
