@extends('layouts.admin')

@section('title', 'BrokerBase - Dealer Dashboard')

@section('header-content')
<h2 class="text-slate-900 text-lg font-bold leading-tight">Welcome back, Elite Homes</h2>
<p class="text-sm text-gray-500 hidden sm:block">Here's what's happening today.</p>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
<h1 class="text-slate-900 text-3xl font-black leading-tight tracking-tight">Dashboard</h1>
@include('components.AddPropertyModal')
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
<div class="flex items-center justify-between">
<div>
<p class="text-sm text-gray-600">Total Properties</p>
<p class="text-2xl font-bold text-gray-900">24</p>
</div>
<div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
<span class="material-symbols-outlined text-blue-600">warehouse</span>
</div>
</div>
<div class="mt-4 flex items-center text-sm">
<span class="text-green-600 font-medium">+12%</span>
<span class="text-gray-500 ml-1">from last month</span>
</div>
</div>

<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
<div class="flex items-center justify-between">
<div>
<p class="text-sm text-gray-600">Active Listings</p>
<p class="text-2xl font-bold text-gray-900">18</p>
</div>
<div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
<span class="material-symbols-outlined text-green-600">visibility</span>
</div>
</div>
<div class="mt-4 flex items-center text-sm">
<span class="text-green-600 font-medium">+8%</span>
<span class="text-gray-500 ml-1">from last month</span>
</div>
</div>

<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
<div class="flex items-center justify-between">
<div>
<p class="text-sm text-gray-600">Properties Sold</p>
<p class="text-2xl font-bold text-gray-900">6</p>
</div>
<div class="h-12 w-12 bg-amber-100 rounded-full flex items-center justify-center">
<span class="material-symbols-outlined text-amber-600">check_circle</span>
</div>
</div>
<div class="mt-4 flex items-center text-sm">
<span class="text-green-600 font-medium">+3</span>
<span class="text-gray-500 ml-1">this month</span>
</div>
</div>
</div>
</div>

<!-- Recent Listings Table -->
<div class="flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
<div class="p-6 border-b border-gray-100 flex justify-between items-center">
<h3 class="text-lg font-bold text-slate-900">Recent Listings</h3>
<a class="text-sm text-royal-blue font-medium hover:underline" href="#">View All</a>
</div>
<div class="overflow-x-auto">
<table class="w-full min-w-[700px]">
<thead class="bg-gray-50">
<tr>
<th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Property</th>
<th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
<th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
<th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-100">
<!-- Row 1 -->
<tr class="hover:bg-gray-50/50 transition-colors">
<td class="px-6 py-4 whitespace-nowrap">
<div class="flex items-center gap-4">
<div class="w-16 h-12 rounded-lg bg-cover bg-center shrink-0" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCToc03ewI-R-MLH1VeaILvpzcsPrzcNl35tCllapTZwSgSR39FEB-O03otqjWOPaQcd-FItQ4ORhThF5Ph3HmSpDPRgp1FgiERkSyWa_HVyO0UAkX8ApEuSzr8Z15ELVzKGK2pqUeHYTW4Ar_ZjAVyN-hy7GRG9SX86kKSlbXaRaHpijSfGxAa_XmtxQxozG8aaQRu7OlewhaXfNoZLh9hcU0aPLn-Us23Btb3P7qcH_zGOl8RrHEakkzwn2n7KGBDwjm-oBB_f70f');"></div>
<div class="flex flex-col">
<span class="text-sm font-bold text-slate-900">Seaside Villa</span>
<span class="text-xs text-gray-500">12 Ocean Dr, Malibu</span>
</div>
</div>
</td>
<td class="px-6 py-4 whitespace-nowrap">
<span class="text-sm font-bold text-royal-blue">$2,500,000</span>
</td>
<td class="px-6 py-4 whitespace-nowrap">
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
<span class="size-1.5 rounded-full bg-emerald-600"></span>
Available
</span>
</td>
<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
<div class="flex items-center justify-end gap-2">
<button class="p-2 text-gray-400 hover:text-royal-blue hover:bg-blue-50 rounded-full transition-colors">
<span class="material-symbols-outlined text-[20px]">edit</span>
</button>
<button class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-full transition-colors">
<span class="material-symbols-outlined text-[20px]">share</span>
</button>
</div>
</td>
</tr>
<!-- Row 2 -->
<tr class="hover:bg-gray-50/50 transition-colors">
<td class="px-6 py-4 whitespace-nowrap">
<div class="flex items-center gap-4">
<div class="w-16 h-12 rounded-lg bg-cover bg-center shrink-0" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDdhjV4Kk0pc9BPnmOBfpnmUfWfZXgnJiKxKd0fBo3FkvF2Dw1_m-VUSfwR9RXPR9Mh_K6UP3m5MdbMtnevhMoJrYW9VcXQ0KkJcc3q3jt0T-8ADSIPTYb-T_SxL4I8HInHQ0ngU4p9h80Do3Ac7mXpX37jGdGqg4KkyMS2oZunrbaz8rrealQBNZd2sfkiIoKIxqvnAwuxwKG3267dMdaaTT_4aAlFfUdds9vqolu76zP8xpGfu04YE81DWahgI_ejytJbVjAk6OzQ');"></div>
<div class="flex flex-col">
<span class="text-sm font-bold text-slate-900">Downtown Loft</span>
<span class="text-xs text-gray-500">45 Main St, Seattle</span>
</div>
</div>
</td>
<td class="px-6 py-4 whitespace-nowrap">
<span class="text-sm font-bold text-royal-blue">$850,000</span>
</td>
<td class="px-6 py-4 whitespace-nowrap">
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
<span class="size-1.5 rounded-full bg-rose-600"></span>
Sold
</span>
</td>
<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
<div class="flex items-center justify-end gap-2">
<button class="p-2 text-gray-400 hover:text-royal-blue hover:bg-blue-50 rounded-full transition-colors">
<span class="material-symbols-outlined text-[20px]">edit</span>
</button>
<button class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-full transition-colors">
<span class="material-symbols-outlined text-[20px]">share</span>
</button>
</div>
</td>
</tr>
<!-- Row 3 -->
<tr class="hover:bg-gray-50/50 transition-colors">
<td class="px-6 py-4 whitespace-nowrap">
<div class="flex items-center gap-4">
<div class="w-16 h-12 rounded-lg bg-cover bg-center shrink-0" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBfzjxBa0QRGyirjWArZ9PEzGMK-80-ujNe0C_d6PslGsrAKlwY51dm59CQrX5PCl5EmDtYgaw76ydR5BVo2KlkyFVZpKuwn7Sh8Nm0VjWjn16rWRidqJgL0WWzZ0-M08Ohldedy0d1fJU65k9eHxppvivlAKcb7cia0ZfoVeIrKCy1HLsc7bVuQvjl2MFW_gOPLHw8H5Aw-3bnNHaZLFlwHgFk5BvZFtwSrDub-FuNFjfndRlF1bH6SyWGfLChuh_9OyViHfocIrBx');"></div>
<div class="flex flex-col">
<span class="text-sm font-bold text-slate-900">The Oaks Estate</span>
<span class="text-xs text-gray-500">892 Oak Ln, Austin</span>
</div>
</div>
</td>
<td class="px-6 py-4 whitespace-nowrap">
<span class="text-sm font-bold text-royal-blue">$1,200,000</span>
</td>
<td class="px-6 py-4 whitespace-nowrap">
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
<span class="size-1.5 rounded-full bg-gray-500"></span>
Draft
</span>
</td>
<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
<div class="flex items-center justify-end gap-2">
<button class="p-2 text-gray-400 hover:text-royal-blue hover:bg-blue-50 rounded-full transition-colors">
<span class="material-symbols-outlined text-[20px]">edit</span>
</button>
<button class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-full transition-colors">
<span class="material-symbols-outlined text-[20px]">share</span>
</button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
</div>
@endsection