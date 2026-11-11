<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Components;

use Illuminate\Contracts\Support\Htmlable;
use Juaniquillo\BaseBackendComponent\Concerns\SerializesContents;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;
use Juaniquillo\BaseBackendComponent\Contracts\ContentsComponent;

use function Juaniquillo\BackendComponents\backendComponentNamespace;

final class DefaultContentsComponent implements ContentsComponent, Htmlable
{
    use SerializesContents;

    /**
     * @param  array<string|int, int|string|CompoundComponent|BackendComponent>  $contents
     */
    public function __construct(
        private array $contents
    ) {}

    /**
     * @return array<string|int, string|int|CompoundComponent|BackendComponent>
     */
    public function getContents(): array
    {
        return $this->contents;
    }

    public function toHtml(): string
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
}
