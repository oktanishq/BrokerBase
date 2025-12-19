<aside class="hidden lg:flex flex-col w-64 bg-royal-blue text-white h-full fixed left-0 top-0 bottom-0 z-30 shadow-xl transition-all duration-300">
<div class="p-6 flex items-center gap-3">
<div class="flex items-center justify-center size-10 rounded-full bg-amber-500/20 text-amber-500">
<span class="material-symbols-outlined text-amber-500">apartment</span>
</div>
<h1 class="text-xl font-bold tracking-wide text-white">BrokerBase</h1>
</div>
<nav class="flex flex-col gap-2 px-4 mt-4 flex-1">
@php
    $currentPath = request()->path();
    $isDashboard = $currentPath === 'admin/dashboard';
    $isInventory = $currentPath === 'admin/inventory';
    $isLeads = $currentPath === 'admin/leads';
@endphp

<a class="flex items-center gap-3 px-4 py-3 rounded-full transition-all {{ $isDashboard ? 'bg-[#172554] border-l-4 border-amber-500 shadow-inner text-white' : 'hover:bg-white/10 text-gray-300 hover:text-white' }}" href="{{ url('/admin/dashboard') }}">
<span class="material-symbols-outlined {{ $isDashboard ? 'text-amber-500' : '' }}">dashboard</span>
<span class="text-sm font-medium {{ $isDashboard ? 'text-white' : '' }}">Dashboard</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 rounded-full transition-all {{ $isInventory ? 'bg-[#172554] border-l-4 border-amber-500 shadow-inner text-white' : 'hover:bg-white/10 text-gray-300 hover:text-white' }}" href="{{ url('/admin/inventory') }}">
<span class="material-symbols-outlined {{ $isInventory ? 'text-amber-500' : '' }}">warehouse</span>
<span class="text-sm font-medium {{ $isInventory ? 'text-white' : '' }}">My Inventory</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 rounded-full transition-all {{ $isLeads ? 'bg-[#172554] border-l-4 border-amber-500 shadow-inner text-white' : 'hover:bg-white/10 text-gray-300 hover:text-white' }}" href="{{ url('/admin/leads') }}">
<span class="material-symbols-outlined {{ $isLeads ? 'text-amber-500' : '' }}">group</span>
<span class="text-sm font-medium {{ $isLeads ? 'text-white' : '' }}">Leads</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 rounded-full hover:bg-white/10 text-gray-300 hover:text-white transition-colors" href="#">
<span class="material-symbols-outlined">pie_chart</span>
<span class="text-sm font-medium">Analytics</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 rounded-full hover:bg-white/10 text-gray-300 hover:text-white transition-colors" href="#">
<span class="material-symbols-outlined">settings</span>
<span class="text-sm font-medium">Settings</span>
</a>
</nav>
<div class="p-4 mt-auto border-t border-white/10">
<div class="flex items-center gap-3 px-4 py-3 rounded-full hover:bg-white/10 cursor-pointer">
<span class="material-symbols-outlined text-gray-400">logout</span>
<span class="text-sm font-medium text-gray-300">Log Out</span>
</div>
</div>
</aside>