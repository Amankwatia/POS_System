@props([
    'text' => '',
    'type' => 'default', // default, success, warning, danger, info
])

@php
    $types = [
        'default' => 'bg-gray-100 text-content-secondary',
        'success' => 'badge-success',
        'warning' => 'badge-warning',
        'danger' => 'badge-danger',
        'info' => 'badge-info',
    ];
@endphp

<span {{ $attributes->merge(['class' => "badge {$types[$type]}"]) }}>
    {{ $text ?: $slot }}
</span>
