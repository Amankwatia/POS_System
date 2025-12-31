@props([
    'title' => '',
    'subtitle' => '',
    'action' => null,
    'actionLabel' => 'View all',
    'actionHref' => '#',
    'noPadding' => false,
])

<div {{ $attributes->merge(['class' => 'card overflow-hidden']) }}>
    @if($title)
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <div>
                <h3 class="text-base font-semibold text-content">{{ $title }}</h3>
                @if($subtitle)
                    <p class="text-sm text-content-muted mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            @if($action)
                {{ $action }}
            @elseif($actionHref !== '#')
                <a href="{{ $actionHref }}" class="text-sm font-medium text-primary hover:text-primary-800 transition">
                    {{ $actionLabel }}
                </a>
            @endif
        </div>
    @endif
    
    <div class="{{ $noPadding ? '' : 'p-5' }}">
        {{ $slot }}
    </div>
</div>
