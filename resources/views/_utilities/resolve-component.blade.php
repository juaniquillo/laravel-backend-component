@php
    /** @var \Juaniquillo\BackendComponents\Contracts\CompoundComponent $component */
    // getComponentPath() omits the "components" view directory
    $path = ($component->getNamespace() ?? '')
        .'components.'
        .\trim($component->getPathOnly().'.'.$component->getName(), '.');
    $attributes = new \Illuminate\View\ComponentAttributeBag($component->getAttributeBag()->getAttributes());
    $slot = $component->processContent();
    $settings = $component->getSettings();
@endphp
@include($path, ['attributes' => $attributes, 'slot' => $slot, 'settings' => $settings])
