<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Components;

use Illuminate\Contracts\Support\Htmlable;
use Juaniquillo\BackendComponents\Concerns\IsLivewireComponent;
use Juaniquillo\BackendComponents\Contracts\LivewireComponent;

use function Juaniquillo\BackendComponents\backendComponentNamespace;

final class DefaultLivewireComponent implements Htmlable, LivewireComponent
{
    use IsLivewireComponent;

    /**
     * @param  class-string  $name
     */
    public function __construct(
        private string $name
    ) {}

    /**
     * @param  class-string  $name
     */
    public static function make(string $name): static
    {
        return new self($name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toHtml(): string
    {
        /**
         * PHPStan bug
         * https://github.com/larastan/larastan/issues/2213
         *
         * @phpstan-ignore argument.type
         */
        return view(backendComponentNamespace().'_utilities.resolve-livewire-component', [
            'component' => $this,
        ])->render();
    }
}
