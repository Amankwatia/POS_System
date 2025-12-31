@props([
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, success, warning, danger, outline
    'size' => 'md', // sm, md, lg
    'href' => null,
    'disabled' => false,
])

@php
    $variants = [
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'success' => 'btn-success',
        'warning' => 'btn-warning',
        'danger' => 'btn-danger',
        'outline' => 'bg-white text-content border border-gray-300 hover:bg-gray-50 focus:ring-2 focus:ring-gray-200',
    ];
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];
    
    $classes = "btn {$variants[$variant]} {$sizes[$size]}";
    if ($disabled) {
        $classes .= ' opacity-50 cursor-not-allowed';
    }
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} @if($disabled) disabled @endif>
        {{ $slot }}
    </button>
@endif
