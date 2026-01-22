<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Concerns;

use Juaniquillo\BackendComponents\Components\DefaultContentsComponent;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Contracts\ContentsComponent;

trait HasSlots
{
    /**
     * @var array<int|string, CompoundComponent|BackendComponent>
     */
    private array $slots = [];

    /**
     * @return array<int|string, CompoundComponent|BackendComponent>
     */
    public function getSlots(): array
    {
        return $this->slots;
    }

    public function getSlot(int|string $name): CompoundComponent|BackendComponent|null
    {
        return $this->getSlots()[$name] ?? null;
    }

    public function setSlot(int|string $name, CompoundComponent|BackendComponent $slot): static
    {
        $this->slots[$name] = $slot;

        return $this;
    }

    /**
     * @param  array<int|string, CompoundComponent|BackendComponent>  $slots
     */
    public function setSlots(array $slots): static
    {
        foreach ($slots as $name => $slot) {
            $this->setSlot($name, $slot);
        }

        return $this;
    }

    public function processSlots(): ContentsComponent
    {
        return new DefaultContentsComponent($this->getSlots());
    }
}
