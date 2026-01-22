@php
    /** @var \Juaniquillo\BackendComponents\Contracts\CompoundComponent $component */
    $name = $component->getName();
    $params = $component->getLivewireParams();
    $key = $component->getLivewireKey();
@endphp

@if ($key)@livewire($name, $params, key($key))@else @livewire($name, $params)@endif