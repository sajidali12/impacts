@props(['active'])

@php
$classes = ($active ?? false)
            ? 'bg-indigo-50 border-indigo-500 text-indigo-700 group flex items-center px-2 py-2 text-sm font-medium border-l-4'
            : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium border-l-4 hover:border-gray-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>