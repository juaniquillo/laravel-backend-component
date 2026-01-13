<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Components;

use Illuminate\Contracts\Support\Htmlable;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Contracts\ContentsComponent;

use function Juaniquillo\BackendComponents\backendComponentNamespace;
use function Juaniquillo\BackendComponents\isComponent;

final class DefaultContentsComponent implements ContentsComponent, Htmlable
{
    /**
     * @param  array<string|int, int|string|CompoundComponent|BackendComponent>  $contents
     */
    public function __construct(
        private array $contents
    ) {}

    public function toHtml()
    {
        /** @var non-falsy-string $view */
        $view = backendComponentNamespace().'_utilities.resolve-content';

        /**
         * PHPStan bug
         * https://github.com/larastan/larastan/issues/2213
         *
         * @phpstan-ignore argument.type
         */
        return view($view)
            ->with('contents', $this->contents)
            ->render();

    }

    /**
     * @return array<int|string, array<string, array<string, mixed>|bool|int|string|null>|int|string>
     */
    public function toArray(): array
    {
        $contentArray = [];

        foreach ($this->contents as $key => $content) {
            $contentArray[$key] = isComponent($content) ? $content->toArray() : $content;
        }

        return $contentArray;
    }
}
