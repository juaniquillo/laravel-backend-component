@php
    /** @var \Juaniquillo\BackendComponents\Contracts\LivewireComponent $component */
    $name = $component->getName();
    $params = $component->getLivewireParams();
    $key = $component->getLivewireKey();
@endphp

@if ($key)@livewire($name, $params, key($key))@else @livewire($name, $params)@endif