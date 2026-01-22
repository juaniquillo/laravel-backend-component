@props([
    'attrs' => null,
])

<?php
    /** @var \Juaniquillo\BackendComponents\Components\DefaultAttributeBag $attrs */
?>

@php
    $content = null;
    $slot = $slot ?? null;
    
    if($attrs) {
        $content = $attrs->content;

    }

@endphp
{{ $content }}