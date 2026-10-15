@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-3 py-2 rounded-lg text-sm font-semibold text-white bg-white/20'
    : 'inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium text-green-50 hover:text-white hover:bg-white/10 transition duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>