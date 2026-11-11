<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Concerns;

use Juaniquillo\BaseBackendComponent\Concerns\IsBackendComponent as BaseIsBackendComponent;

trait IsBackendComponent
{
    use BaseIsBackendComponent;

    public function __toString(): string
    {
        $json = \json_encode($this->toArray());

        return $json ? $json : '';
    }
}
