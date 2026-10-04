@props([
    'attrs' => null,
])

<x-backend-component::form.text {{ $attributes->merge(['type' => 'tel']) }} :attrs="$attrs" />
