@props([
    'title' => 'Sales Overview',
    'subtitle' => 'Last 7 days',
    'data' => [],
    'labels' => [],
    'type' => 'bar', // bar, line
])

@php
    // Generate sample data if none provided
    $chartData = count($data) > 0 ? $data : [120, 190, 300, 250, 220, 180, 350];
    $chartLabels = count($labels) > 0 ? $labels : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    $maxValue = max($chartData) ?: 1;
@endphp

<div {{ $attributes->merge(['class' => 'card p-5']) }}>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-base font-semibold text-content">{{ $title }}</h3>
            <p class="text-sm text-content-muted">{{ $subtitle }}</p>
        </div>
    </div>
    
    <div class="relative">
        <!-- Simple CSS Bar Chart -->
        <div class="flex items-end justify-between gap-2 h-48">
            @foreach($chartData as $index => $value)
                <div class="flex-1 flex flex-col items-center gap-2">
                    <div class="w-full bg-gray-100 rounded-t-lg overflow-hidden flex flex-col justify-end" style="height: 160px;">
                        <div class="w-full bg-primary rounded-t-lg transition-all duration-500 hover:bg-primary-800" 
                             style="height: {{ ($value / $maxValue) * 100 }}%;"
                             title="₵{{ number_format($value, 0) }}">
                        </div>
                    </div>
                    <span class="text-xs text-content-muted">{{ $chartLabels[$index] ?? '' }}</span>
                </div>
            @endforeach
        </div>
        
        <!-- Y-axis labels -->
        <div class="absolute left-0 top-0 -ml-8 h-40 flex flex-col justify-between text-xs text-content-muted">
            <span>₵{{ number_format($maxValue, 0) }}</span>
            <span>₵{{ number_format($maxValue / 2, 0) }}</span>
            <span>$0</span>
        </div>
    </div>
</div>
