@props(['title', 'value', 'trend' => null, 'trendValue' => null, 'icon', 'iconColor' => 'blue'])

@php
$iconColorClasses = [
    'blue' => 'bg-blue-50 text-royal-blue',
    'green' => 'bg-green-50 text-green-600',
    'amber' => 'bg-amber-50 text-amber-600',
    'gray' => 'bg-gray-100 text-gray-600'
];

$trendColorClasses = [
    'up' => 'text-green-600',
    'down' => 'text-red-500',
    'stable' => 'text-gray-500'
];

$iconClass = $iconColorClasses[$iconColor] ?? $iconColorClasses['blue'];
$trendClass = $trend ? $trendColorClasses[$trend] : $trendColorClasses['stable'];
@endphp

<div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between gap-4">
    <div class="flex justify-between items-start">
        <div>
            <p class="text-gray-500 font-medium text-xs uppercase tracking-wide">{{ $title }}</p>
            <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $value }}</h3>
        </div>
        <span class="{{ $iconClass }} p-2 rounded-lg">
            <span class="material-symbols-outlined text-[24px]">{{ $icon }}</span>
        </span>
    </div>
    
    @if($trend && $trendValue)
        <div class="flex items-center gap-1 text-sm">
            <span class="{{ $trendClass }} font-bold flex items-center">
                <span class="material-symbols-outlined text-[16px]">
                    @if($trend === 'up') trending_up
                    @elseif($trend === 'down') trending_down  
                    @else remove
                    @endif
                </span>
                {{ $trendValue }}%
            </span>
            <span class="text-gray-400">vs last period</span>
        </div>
    @else
        <div class="flex items-center gap-1 text-sm">
            <span class="{{ $trendClass }} flex items-center">
                <span class="material-symbols-outlined text-[16px]">remove</span>
                Stable
            </span>
        </div>
    @endif
</div>