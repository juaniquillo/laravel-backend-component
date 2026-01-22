<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Contracts;

interface ContentComponent
{
    public function getContent(string|int $key): CompoundComponent|BackendComponent|int|string|null;

    /**
     * @return array<string|int, string|int|CompoundComponent|BackendComponent>
     */
    public function getContents(): array;

    public function setContent(int|string|CompoundComponent|BackendComponent $content, string|int|null $key = null): static;

    /**
     * @param  array<string|int, string|int|CompoundComponent|BackendComponent>  $contents
     */
    public function setContents(array $contents): static;

    public function processContent(): ContentsComponent;
}
