@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-green-600 text-start text-base font-semibold text-green-700 bg-green-50'
    : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 transition duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>