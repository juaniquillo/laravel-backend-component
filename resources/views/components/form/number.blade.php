@props([
    'attrs' => null,
])

<x-backend-component::form.text {{ $attributes->merge(['type' => 'number']) }} :attrs="$attrs" />
