@props([
    'attrs' => null,
])

<?php
    /** @var \Juaniquillo\BackendComponents\Components\DefaultAttributeBag $attrs */
?>

@php
    $serverAttrs = [
        'type' => 'text',
    ];

    if($attrs) {
        $serverAttrs = array_merge($serverAttrs, $attrs->getAttributes());
    }

@endphp

<input {{ $attributes->merge($serverAttrs) }}/>