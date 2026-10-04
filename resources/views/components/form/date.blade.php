@props([
    'attrs' => null,
])

<x-backend-component::form.text {{ $attributes->merge(['type' => 'date']) }} :attrs="$attrs" />
