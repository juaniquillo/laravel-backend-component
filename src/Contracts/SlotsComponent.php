<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Contracts;

interface SlotsComponent
{
    /**
     * @return array<int|string, CompoundComponent|BackendComponent>
     */
    public function getSlots(): array;

    public function getSlot(int|string $name): CompoundComponent|BackendComponent|null;

    public function setSlot(int|string $name, CompoundComponent|BackendComponent $slot): static;

    /**
     * @param  array<int|string, CompoundComponent|BackendComponent>  $slots
     */
    public function setSlots(array $slots): static;

    public function processSlots(): ContentsComponent;
}
