<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Contracts;

interface AttributeBag
{
    /** @return array<string, string> */
    public function getAttributes(): array;
}
