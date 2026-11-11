<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Deserializers;

use Juaniquillo\BaseBackendComponent\Concerns\DeserializesComponents;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;
use Juaniquillo\BaseBackendComponent\Contracts\IndividualComponent;

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
