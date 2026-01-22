@props([
    'attrs' => null,
])

<?php
    /** @var \Juaniquillo\BackendComponents\Components\DefaultAttributeBag $attrs */
?>

@php
    $serverAttrs = [];

    if($attrs) {
        $serverAttrs = $attrs->getAttributes();
    }
@endphp

<img {{ $attributes->merge($serverAttrs) }} />