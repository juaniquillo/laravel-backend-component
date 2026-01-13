<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Concerns;

trait IsLivewireComponent
{
    private bool $isLiveWire = false;

    private ?string $livewireKey = null;

    /**
     * @var array<string, mixed>
     */
    private array $livewireParams = [];

    public function setLivewire(bool $livewire = true): static
    {
        $this->isLiveWire = $livewire;

        return $this;
    }

    /**
     * @param  array<string, mixed>  $livewireParams
     */
    public function setLivewireParams(array $livewireParams): static
    {
        $this->livewireParams = $livewireParams;

        return $this;
    }

    public function setLivewireKey(string $livewireKey): static
    {
        $this->livewireKey = $livewireKey;

        return $this;
    }

    public function isLivewire(): bool
    {
        return $this->isLiveWire;
    }

    /**
     * @return array<string, mixed>
     */
    public function getLivewireParams(): array
    {
        return $this->livewireParams;
    }

    public function getLivewireKey(): ?string
    {
        return $this->livewireKey;
    }
}
