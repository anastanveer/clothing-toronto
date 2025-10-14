@props([
    'tag' => 'main',
    'class' => null,
])

@php
    $tag = $tag ?: 'main';
@endphp

<{{ $tag }} {{ $attributes->class($class) }}>
    {{ $slot }}
</{{ $tag }}>
