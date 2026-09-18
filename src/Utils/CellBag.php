<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Utils;

use Juaniquillo\BackendComponents\Contracts\BackendComponent;

final readonly class CellBag
{
    /**
     * @param  array<string, int|string|null>  $attributes
     * @param  array<string, string|array<string|int, string>>|null  $theme
     */
    public function __construct(
        public int|string|BackendComponent $content,
        public ?array $theme = null,
        public ?array $attributes = null
    ) {}
}
