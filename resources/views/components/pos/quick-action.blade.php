@props([
    'label' => '',
    'icon' => null,
    'href' => '#',
    'variant' => 'primary', // primary, secondary, outline
])

@php
    $variants = [
        'primary' => 'border-primary-200 bg-primary-50 hover:border-primary hover:bg-primary-100 text-primary-800',
        'secondary' => 'border-gray-200 bg-gray-50 hover:border-gray-300 hover:bg-gray-100 text-content',
        'outline' => 'border-gray-200 bg-white hover:border-primary hover:bg-primary-50 text-content hover:text-primary',
    ];
    
    $iconBg = [
        'primary' => 'bg-primary text-white',
        'secondary' => 'bg-gray-600 text-white',
        'outline' => 'bg-gray-100 text-content-secondary group-hover:bg-primary group-hover:text-white',
    ];
@endphp

<a href="{{ $href }}" 
   {{ $attributes->merge(['class' => "quick-action group {$variants[$variant]}"]) }}>
    @if($icon)
        <span class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg transition {{ $iconBg[$variant] }}">
            {!! $icon !!}
        </span>
    @endif
    <span class="text-sm font-semibold text-center">{{ $label }}</span>
    @if($slot->isNotEmpty())
        <span class="text-xs text-center opacity-75">{{ $slot }}</span>
    @endif
</a>
