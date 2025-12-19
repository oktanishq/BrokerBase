<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>BrokerBase - Dealer Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
@vite('resources/css/app.css')
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-slate-900 overflow-hidden">
<div class="flex h-screen w-full bg-background-light">
<aside class="hidden lg:flex flex-col w-64 bg-royal-blue text-white h-full fixed left-0 top-0 bottom-0 z-30 shadow-xl transition-all duration-300">
<div class="p-6 flex items-center gap-3">
<div class="flex items-center justify-center size-10 rounded-full bg-amber-500/20 text-amber-500">
<span class="material-symbols-outlined text-amber-500">apartment</span>
</div>
<h1 class="text-xl font-bold tracking-wide text-white">BrokerBase</h1>
</div>
<nav class="flex flex-col gap-2 px-4 mt-4 flex-1">
<a class="flex items-center gap-3 px-4 py-3 rounded-full bg-[#172554] border-l-4 border-amber-500 transition-all shadow-inner" href="#">
<span class="material-symbols-outlined text-amber-500">dashboard</span>
<span class="text-sm font-medium text-white">Dashboard</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 rounded-full hover:bg-white/10 text-gray-300 hover:text-white transition-colors" href="#">
<span class="material-symbols-outlined">warehouse</span>
<span class="text-sm font-medium">My Inventory</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 rounded-full hover:bg-white/10 text-gray-300 hover:text-white transition-colors" href="#">
<span class="material-symbols-outlined">group</span>
<span class="text-sm font-medium">Leads</span>
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
<div class="flex flex-col flex-1 h-full lg:ml-64 relative overflow-hidden bg-gray-50">
<header class="flex items-center justify-between bg-white border-b border-gray-100 px-6 py-4 shadow-sm sticky top-0 z-20">
<div class="flex items-center gap-4">
<button class="lg:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-full">
<span class="material-symbols-outlined">menu</span>
</button>
<div class="flex flex-col">
<nav aria-label="Breadcrumb" class="hidden sm:flex">
<ol class="inline-flex items-center space-x-1 md:space-x-2 text-sm text-gray-500">
<li class="inline-flex items-center">
<a class="hover:text-royal-blue transition-colors" href="#">Home</a>
</li>
<li>
<div class="flex items-center">
<span class="material-symbols-outlined text-[16px] text-gray-400">chevron_right</span>
<span class="ml-1 font-medium text-gray-700">Dashboard</span>
</div>
</li>
</ol>
</nav>
</div>
</div>
<div class="flex items-center gap-4">
<button class="flex items-center justify-center size-10 rounded-full bg-gray-50 hover:bg-gray-200 text-slate-700 transition-colors relative">
<span class="material-symbols-outlined text-[20px]">notifications</span>
<span class="absolute top-2 right-2 size-2 bg-red-500 rounded-full border-2 border-white"></span>
</button>
<div class="bg-center bg-no-repeat bg-cover rounded-full size-10 ring-2 ring-gray-100 cursor-pointer" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDjlFF_nSTOQN2xN5XEhoei2r1xmo6006_o8UoGMAFUfEAomAjyJR-_bXnIPonwd3cqDG7sOU8o_DGuG6ynBK32KcH-lRZpx1OAvvrV7EALzre8oOHD4wHQDNcs1u-RqUpqp6rABg-PLwMMJpYI1mwd0rmsHsf0SI7DMC0X71sycCni1WxVUk61lnXtb-Wzonan3tvT7xcDV3vnvIuNyz4n4mt6oBDAaqb4Ch5zP_c1FPKCfCmqMwaC598j6zQlRK21aawjBmED-Tjo');"></div>
</div>
</header>
<main class="flex-1 overflow-y-auto p-4 md:p-8">
<div class="max-w-[1400px] mx-auto flex flex-col gap-6">
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
<h1 class="text-slate-900 text-3xl font-black leading-tight tracking-tight">Dashboard</h1>
<button class="flex items-center justify-center gap-2 rounded-full h-11 px-6 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-lg shadow-blue-200 transition-all transform hover:scale-105">
<span class="material-symbols-outlined text-[20px]">add</span>
<span>Add New Property</span>
</button>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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
<span class="material-symbols-outlined text-amber-600">sold</span>
</div>
</div>
<div class="mt-4 flex items-center text-sm">
<span class="text-green-600 font-medium">+3</span>
<span class="text-gray-500 ml-1">this month</span>
</div>
</div>

<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
<div class="flex items-center justify-between">
<div>
<p class="text-sm text-gray-600">Total Views</p>
<p class="text-2xl font-bold text-gray-900">1,248</p>
</div>
<div class="h-12 w-12 bg-purple-100 rounded-full flex items-center justify-center">
<span class="material-symbols-outlined text-purple-600">trending_up</span>
</div>
</div>
<div class="mt-4 flex items-center text-sm">
<span class="text-green-600 font-medium">+24%</span>
<span class="text-gray-500 ml-1">from last month</span>
</div>
</div>
</div>

<!-- Recent Activity -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
<h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h2>
<div class="space-y-4">
<div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
<span class="material-symbols-outlined text-green-600">add_circle</span>
<div class="flex-1">
<p class="text-sm font-medium text-gray-900">New property added</p>
<p class="text-xs text-gray-500">Sunset Villa - Luxury Oceanfront Estate</p>
</div>
<span class="text-xs text-gray-400">2 hours ago</span>
</div>
<div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
<span class="material-symbols-outlined text-blue-600">edit</span>
<div class="flex-1">
<p class="text-sm font-medium text-gray-900">Property updated</p>
<p class="text-xs text-gray-500">City Apartment - Modern Downtown Living</p>
</div>
<span class="text-xs text-gray-400">1 day ago</span>
</div>
<div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
<span class="material-symbols-outlined text-amber-600">visibility</span>
<div class="flex-1">
<p class="text-sm font-medium text-gray-900">Property viewed</p>
<p class="text-xs text-gray-500">Commercial Office Space</p>
</div>
<span class="text-xs text-gray-400">2 days ago</span>
</div>
</div>
</div>

<!-- Quick Actions -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
<h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
<button class="flex items-center gap-3 p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
<span class="material-symbols-outlined text-blue-600">add</span>
<span class="text-sm font-medium text-gray-900">Add Property</span>
</button>
<button class="flex items-center gap-3 p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
<span class="material-symbols-outlined text-green-600">upload</span>
<span class="text-sm font-medium text-gray-900">Import Properties</span>
</button>
<button class="flex items-center gap-3 p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
<span class="material-symbols-outlined text-purple-600">analytics</span>
<span class="text-sm font-medium text-gray-900">View Reports</span>
</button>
</div>
</div>
</div>
</main>
</div>
</div>
</body>
</html>