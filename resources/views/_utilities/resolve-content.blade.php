@php
    $contents = $contents ?? [];
@endphp
{!! implode('', array_map(fn ($content) => e($content), $contents)) !!}