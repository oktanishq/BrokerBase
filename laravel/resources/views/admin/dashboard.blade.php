@extends('layouts.admin')

@section('title', 'BrokerBase - Dealer Dashboard')

@section('header-content')
<h2 class="text-slate-900 text-lg font-bold leading-tight">Welcome back, Elite Homes</h2>
<p class="text-sm text-gray-500 hidden sm:block">Here's what's happening today.</p>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
<h1 class="text-slate-900 text-3xl font-black leading-tight tracking-tight">Dashboard</h1>
<x-admin.AddPropertyModal />
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
<div class="flex items-center justify-between">
<div>
<p class="text-sm text-gray-600">Total Properties</p>
<p class="text-2xl font-bold text-gray-900">{{ $totalProperties }}</p>
</div>
<div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
<span class="material-symbols-outlined text-blue-600">warehouse</span>
</div>
</div>
<div class="mt-4 flex items-center text-sm">
@if($totalGrowthPercent >= 0)
<span class="text-green-600 font-medium">+{{ $totalGrowthPercent }}%</span>
@else
<span class="text-red-600 font-medium">{{ $totalGrowthPercent }}%</span>
@endif
<span class="text-gray-500 ml-1">from last month</span>
</div>
</div>

<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
<div class="flex items-center justify-between">
<div>
<p class="text-sm text-gray-600">Active Listings</p>
<p class="text-2xl font-bold text-gray-900">{{ $activeListings }}</p>
</div>
<div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
<span class="material-symbols-outlined text-green-600">visibility</span>
</div>
</div>
<div class="mt-4 flex items-center text-sm">
@if($activeGrowthPercent >= 0)
<span class="text-green-600 font-medium">+{{ $activeGrowthPercent }}%</span>
@else
<span class="text-red-600 font-medium">{{ $activeGrowthPercent }}%</span>
@endif
<span class="text-gray-500 ml-1">from last month</span>
</div>
</div>

<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
<div class="flex items-center justify-between">
<div>
<p class="text-sm text-gray-600">Marked as Sold</p>
<p class="text-2xl font-bold text-gray-900">{{ $propertiesSold }}</p>
</div>
<div class="h-12 w-12 bg-amber-100 rounded-full flex items-center justify-center">
<span class="material-symbols-outlined text-amber-600">check_circle</span>
</div>
</div>
<div class="mt-4 flex items-center text-sm">
@if($soldGrowthCount >= 0)
<span class="text-green-600 font-medium">+{{ $soldGrowthCount }}</span>
@else
<span class="text-red-600 font-medium">{{ $soldGrowthCount }}</span>
@endif
<span class="text-gray-500 ml-1">this month</span>
</div>
</div>
</div>

<!-- Recent Listings Table -->
<div class="flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden -mt-4">
<div class="p-6 border-b border-gray-100 flex justify-between items-center">
<h3 class="text-lg font-bold text-slate-900">Recently Updated</h3>
<a class="text-sm text-royal-blue font-medium hover:underline" href="{{ route('admin.inventory') }}">View All</a>
</div>
<div class="overflow-x-auto">
<table class="w-full">
<thead class="bg-gray-50">
<tr>
<th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Property</th>
<th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Price</th>
<th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Status</th>
<th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-100">
@forelse($recentlyUpdated as $property)
<tr class="hover:bg-gray-50/50 transition-colors">
<td class="px-6 py-4 whitespace-nowrap">
<div class="flex items-center gap-4">
@php
$imageUrl = $property->primary_image_url ?? 'https://via.placeholder.com/64x48/f3f4f6/9ca3af?text=No+Image';
@endphp
<div class="w-16 h-12 rounded-sm bg-cover bg-center shrink-0" style="background-image: url('{{ $imageUrl }}');"></div>
<div class="flex flex-col">
<span class="text-sm font-bold text-slate-900">{{ $property->title }}</span>
<span class="text-xs text-gray-500">{{ $property->address ?? 'No address' }}</span>
</div>
</div>
</td>
<td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
<span class="text-sm font-bold text-royal-blue">₹{{ number_format($property->price) }}</span>
</td>
<td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
@php
$statusClass = match($property->status) {
    'available' => 'bg-emerald-100 text-emerald-800',
    'sold' => 'bg-rose-100 text-rose-800',
    'booked' => 'bg-amber-100 text-amber-800',
    'draft' => 'bg-gray-100 text-gray-600',
    default => 'bg-gray-100 text-gray-600',
};
$statusDotClass = match($property->status) {
    'available' => 'bg-emerald-600',
    'sold' => 'bg-rose-600',
    'booked' => 'bg-amber-600',
    'draft' => 'bg-gray-500',
    default => 'bg-gray-500',
};
$statusLabel = match($property->status) {
    'available' => 'Available',
    'sold' => 'Sold',
    'booked' => 'Booked',
    'draft' => 'Draft',
    default => ucfirst($property->status),
};
@endphp
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
<span class="size-1.5 rounded-full {{ $statusDotClass }}"></span>
{{ $statusLabel }}
</span>
</td>
<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
<div class="flex items-center justify-end gap-2">
<a href="{{ route('admin.inventory', ['edit' => $property->id]) }}" class="p-2 text-gray-400 hover:text-royal-blue hover:bg-blue-50 rounded-full transition-colors">
<span class="material-symbols-outlined text-[20px]">edit</span>
</a>
<button @click="navigator.clipboard.writeText('{{ url('/property/' . $property->id) }}'); $event.target.closest('button').querySelector('.copy-success').style.display = 'inline'; setTimeout(() => { $event.target.closest('button').querySelector('.copy-success').style.display = 'none'; }, 2000)" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-full transition-colors relative">
<span class="material-symbols-outlined text-[20px]">share</span>
<span class="copy-success hidden absolute -top-1 -right-1 bg-green-500 text-white text-xs px-1 rounded">✓</span>
</button>
</div>
</td>
</tr>
@empty
<tr>
<td colspan="4" class="px-6 py-8 text-center text-gray-500">
No properties found. Add your first property to get started.
</td>
</tr>
@endforelse
</tbody>
</table>
</div>
</div>
@endsection
