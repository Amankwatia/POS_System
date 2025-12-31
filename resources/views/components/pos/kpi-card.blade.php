@props([
    'value' => '0',
    'label' => 'Label',
    'icon' => null,
    'trend' => null,
    'trendLabel' => '',
    'variant' => 'default', // default, primary, success, warning, danger
])

@php
    $variants = [
        'default' => 'bg-white border-gray-100',
        'primary' => 'bg-primary text-white border-transparent',
        'success' => 'bg-success text-white border-transparent',
        'warning' => 'bg-warning text-white border-transparent',
        'danger' => 'bg-danger text-white border-transparent',
    ];
    
    $iconBg = [
        'default' => 'bg-gray-100 text-content-secondary',
        'primary' => 'bg-white/20 text-white',
        'success' => 'bg-white/20 text-white',
        'warning' => 'bg-white/20 text-white',
        'danger' => 'bg-white/20 text-white',
    ];
    
    $textColor = $variant === 'default' ? 'text-content' : 'text-white';
    $labelColor = $variant === 'default' ? 'text-content-secondary' : 'text-white/80';
@endphp

<div {{ $attributes->merge(['class' => "kpi-card {$variants[$variant]}"]) }}>
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium {{ $labelColor }} truncate">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold {{ $textColor }} tracking-tight">{{ $value }}</p>
            
            @if($trend !== null)
                <div class="flex items-center gap-1 mt-2">
                    @if($trend > 0)
                        <svg class="w-4 h-4 {{ $variant === 'default' ? 'text-success' : 'text-white' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium {{ $variant === 'default' ? 'text-success' : 'text-white' }}">+{{ $trend }}%</span>
                    @elseif($trend < 0)
                        <svg class="w-4 h-4 {{ $variant === 'default' ? 'text-danger' : 'text-white' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium {{ $variant === 'default' ? 'text-danger' : 'text-white' }}">{{ $trend }}%</span>
                    @else
                        <span class="text-sm {{ $labelColor }}">No change</span>
                    @endif
                    @if($trendLabel)
                        <span class="text-xs {{ $labelColor }}">{{ $trendLabel }}</span>
                    @endif
                </div>
            @endif
        </div>
        
        @if($icon)
            <div class="kpi-icon {{ $iconBg[$variant] }} flex-shrink-0">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
