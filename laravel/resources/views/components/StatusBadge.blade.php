@props(['status', 'animated' => false])

@php
$statusConfig = [
    'new' => [
        'class' => 'bg-amber-50 text-amber-700 border-amber-200',
        'icon' => 'new_releases',
        'label' => 'New'
    ],
    'contacted' => [
        'class' => 'bg-blue-50 text-blue-700 border-blue-200', 
        'icon' => null,
        'label' => 'Contacted'
    ],
    'negotiation' => [
        'class' => 'bg-purple-50 text-purple-700 border-purple-200',
        'icon' => null, 
        'label' => 'Negotiation'
    ],
    'qualified' => [
        'class' => 'bg-green-50 text-green-700 border-green-200',
        'icon' => 'check_circle',
        'label' => 'Qualified'
    ],
    'closed' => [
        'class' => 'bg-gray-50 text-gray-700 border-gray-200',
        'icon' => 'done',
        'label' => 'Closed'
    ]
];

$config = $statusConfig[$status] ?? $statusConfig['new'];
@endphp

<span class="relative inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $config['class'] }}">
    @if($animated)
        <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
        </span>
    @elseif($config['icon'])
        <span class="material-symbols-outlined text-[14px]">{{ $config['icon'] }}</span>
    @endif
    {{ $config['label'] }}
</span>