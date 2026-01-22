<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Contracts;

interface PathComponent
{
    public function getName(): int|string;

    public function useLocal(bool $local = true): static;

    public function getContext(): string;

    public function getNamespace(): ?string;

    public function getPath(): string;

    public function getPathOnly(): ?string;

    public function getComponentPath(): string;

    public function setNamespace(string $namespace): static;

    public function setPath(string $path): static;
}
