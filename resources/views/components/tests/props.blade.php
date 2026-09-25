@props([
    'dataProps' => [],
    'customProp' => null
])

<?php
    /** @var \Juaniquillo\BackendComponents\Components\DefaultAttributeBag $attrs */
?>

@php
    $localAttrs = [];
    $content = null;

    foreach ($dataProps as $key => $value) {
        $localAttrs["data-{$key}"] = $value;
    }

    if($customProp) {
        $localAttrs[$customProp->key] = $customProp->value;
    }
@endphp

<div {{ $attributes->merge($localAttrs) }}>{{ $content }}{{ $slot }}</div>
