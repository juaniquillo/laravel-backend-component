<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Concerns;

use function Juaniquillo\BackendComponents\backendComponentNamespace;
use function Juaniquillo\BackendComponents\isBackedEnum;

trait HasPath
{
    /**
     * Package namespace
     */
    private ?string $namespace = null;

    private ?string $path = null;

    private bool $useLocal = false;

    public function getName(): int|string
    {
        $name = $this->name;

        if (isBackedEnum($name)) {
            return $name->value;
        }

        return $name;
    }

    public function useLocal(bool $local = true): static
    {
        $this->useLocal = $local;

        return $this;
    }

    public function getContext(): string
    {
        return $this->namespace ?? backendComponentNamespace();
    }

    public function getNamespace(): ?string
    {
        return $this->useLocal
            ? null :
            $this->getContext();
    }

    public function getPath(): string
    {
        return $this->getNamespace().$this->getPathOnly();
    }

    public function getPathOnly(): ?string
    {
        return $this->path;
    }

    public function getComponentPath(): string
    {
        return trim(
            string: $this->getPath().'.'.$this->getName(),
            characters: '.',
        );
    }

    public function setNamespace(string $namespace): static
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }
}
