@php
    /** @var \Juaniquillo\BackendComponents\Contracts\CompoundComponent $component */
    /** @var string|null $namespace */
@endphp

@if($component->isLivewire())
    @include( "{$namespace}_utilities.resolve-livewire-component", ['component' => $component])
@else
    @include( "{$namespace}_utilities.resolve-component", ['component' => $component])
@endif 
