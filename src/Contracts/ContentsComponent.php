<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Contracts;

interface ContentsComponent
{
    /**
     * Get content as a string of HTML.
     *
     * @return string
     */
    public function toHtml();

    /**
     * @return array<string, string|int|array<string, string|int>>
     */
    public function toArray(): array;
}
