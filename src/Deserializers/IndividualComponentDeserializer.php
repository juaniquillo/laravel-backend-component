<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Deserializers;

use Juaniquillo\BackendComponents\Concerns\DeserializesComponents;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Contracts\IndividualComponent;

final class IndividualComponentDeserializer
{
    use DeserializesComponents;

    /**
     * @param array{
     *  name:  int|string,
     *  component: class-string<BackendComponent|CompoundComponent>,
     * } $componentArray
     */
    public function initComponent(array $componentArray): BackendComponent|CompoundComponent
    {
        $componentClass = $componentArray['component'];

        return match (true) {
            \in_array(IndividualComponent::class, \class_implements($componentClass)) => (new $componentClass),
            default => (new $componentClass($componentArray['name'])),
        };

    }
}
