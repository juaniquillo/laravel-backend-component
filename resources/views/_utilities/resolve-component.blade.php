@php
    /** @var \Juaniquillo\BackendComponents\Contracts\CompoundComponent $component */
    $path = $component->getComponentPath();
    $componentArray =  $component->getAttributeBag();
@endphp
<x-dynamic-component :component="$path" :attrs="$componentArray" />