@props([
    'title' => 'No data',
    'message' => '',
    'icon' => null,
    'actionLabel' => null,
    'actionHref' => '#',
])

<div {{ $attributes->merge(['class' => 'py-12 text-center']) }}>
    @if($icon)
        <div class="mx-auto w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
            {!! $icon !!}
        </div>
    @else
        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
    @endif
    
    <h3 class="mt-4 text-sm font-medium text-content">{{ $title }}</h3>
    
    @if($message)
        <p class="mt-1 text-sm text-content-muted">{{ $message }}</p>
    @endif
    
    @if($actionLabel)
        <div class="mt-4">
            <a href="{{ $actionHref }}" class="btn btn-primary">
                {{ $actionLabel }}
            </a>
        </div>
    @endif
    
    {{ $slot }}
</div>
