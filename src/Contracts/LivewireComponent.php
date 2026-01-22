<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Contracts;

interface LivewireComponent
{
    public function setLivewire(bool $livewire = true): static;

    public function setLivewireKey(string $livewireKey): static;

    /**
     * @param  array<string, mixed>  $livewireParams
     */
    public function setLivewireParams(array $livewireParams): static;

    public function isLivewire(): bool;

    public function getLivewireKey(): ?string;

    /**
     * @return array<string, mixed>
     */
    public function getLivewireParams(): array;
}
