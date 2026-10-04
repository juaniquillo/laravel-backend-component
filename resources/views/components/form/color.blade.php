@props([
    'attrs' => null,
])

<x-backend-component::form.text {{ $attributes->merge(['type' => 'color']) }} :attrs="$attrs" />
