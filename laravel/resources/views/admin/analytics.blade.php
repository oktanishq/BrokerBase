@extends('layouts.admin')

@section('title', 'BrokerBase Dealer Analytics')

@section('head')
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "primary": "#f59e0b",
                    "background-light": "#f9fafb",
                    "background-dark": "#23220f",
                    "royal-blue": "#1e3a8a",
                },
                fontFamily: {
                    "display": ["Spline Sans", "sans-serif"]
                },
                borderRadius: {"DEFAULT": "0.5rem", "lg": "0.75rem", "xl": "1rem", "full": "9999px"},
            },
        },
    }
</script>
@endsection

@section('header-content')
<nav aria-label="Breadcrumb" class="hidden sm:flex">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 text-sm text-gray-500">
        <li class="inline-flex items-center">
            <a class="hover:text-royal-blue transition-colors" href="/admin/dashboard">Home</a>
        </li>
        <li>
            <div class="flex items-center">
                <span class="material-symbols-outlined text-[16px] text-gray-400">chevron_right</span>
                <span class="ml-1 font-medium text-gray-700">Analytics</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
                
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4">
                    <div>
                        <h1 class="text-slate-900 text-3xl font-black leading-tight tracking-tight">Performance Overview</h1>
                        <p class="text-gray-500 mt-1 text-sm">Track your property listings and engagement metrics.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button class="flex items-center gap-2 bg-white border border-gray-200 text-slate-700 px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors shadow-sm focus:ring-2 focus:ring-royal-blue focus:ring-offset-1">
                            <span class="material-symbols-outlined text-[20px] text-gray-400">calendar_today</span>
                            <span>Last 30 Days</span>
                            <span class="material-symbols-outlined text-[20px] text-gray-400">expand_more</span>
                        </button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <!-- Total Views Card -->
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between gap-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 font-medium text-xs uppercase tracking-wide">Total Views</p>
                                <h3 class="text-3xl font-bold text-slate-900 mt-1">2,450</h3>
                            </div>
                            <span class="bg-blue-50 text-royal-blue p-2 rounded-lg">
                                <span class="material-symbols-outlined text-[24px]">visibility</span>
                            </span>
                        </div>
                        <div class="flex items-center gap-1 text-sm">
                            <span class="text-green-600 font-bold flex items-center">
                                <span class="material-symbols-outlined text-[16px]">trending_up</span>
                                12%
                            </span>
                            <span class="text-gray-400">vs last period</span>
                        </div>
                    </div>
                    
                    <!-- WhatsApp Clicks Card -->
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between gap-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 font-medium text-xs uppercase tracking-wide">WhatsApp Clicks</p>
                                <h3 class="text-3xl font-bold text-slate-900 mt-1">85</h3>
                            </div>
                            <span class="bg-green-50 text-green-600 p-2 rounded-lg">
                                <span class="material-symbols-outlined text-[24px]">chat</span>
                            </span>
                        </div>
                        <div class="flex items-center gap-1 text-sm">
                            <span class="text-green-600 font-bold flex items-center">
                                <span class="material-symbols-outlined text-[16px]">trending_up</span>
                                5%
                            </span>
                            <span class="text-gray-400">vs last period</span>
                        </div>
                    </div>
                    
                    <!-- Call Button Clicks Card -->
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between gap-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 font-medium text-xs uppercase tracking-wide">Call Button Clicks</p>
                                <h3 class="text-3xl font-bold text-slate-900 mt-1">42</h3>
                            </div>
                            <span class="bg-amber-50 text-amber-600 p-2 rounded-lg">
                                <span class="material-symbols-outlined text-[24px]">call</span>
                            </span>
                        </div>
                        <div class="flex items-center gap-1 text-sm">
                            <span class="text-red-500 font-bold flex items-center">
                                <span class="material-symbols-outlined text-[16px]">trending_down</span>
                                2%
                            </span>
                            <span class="text-gray-400">vs last period</span>
                        </div>
                    </div>
                    
                    <!-- Engagement Rate Card -->
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between gap-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 font-medium text-xs uppercase tracking-wide">Engagement Rate</p>
                                <h3 class="text-3xl font-bold text-slate-900 mt-1">5.2%</h3>
                            </div>
                            <span class="bg-gray-100 text-gray-600 p-2 rounded-lg">
                                <span class="material-symbols-outlined text-[24px]">touch_app</span>
                            </span>
                        </div>
                        <div class="flex items-center gap-1 text-sm">
                            <span class="text-gray-500 flex items-center">
                                <span class="material-symbols-outlined text-[16px]">remove</span>
                                Stable
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Line Chart: Views vs Inquiries -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
                        <div class="flex flex-col sm:flex-row items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-slate-900">Views vs. Inquiries</h3>
                            <div class="flex items-center gap-4 text-sm mt-2 sm:mt-0">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-royal-blue"></span>
                                    <span class="text-gray-600">Views</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                                    <span class="text-gray-600">Inquiries</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="w-full h-64 bg-gray-50/50 rounded-lg relative overflow-hidden">
                            <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 1000 300">
                                <!-- Grid lines -->
                                <line stroke="#e5e7eb" stroke-width="1" x1="0" x2="1000" y1="50" y2="50"></line>
                                <line stroke="#e5e7eb" stroke-width="1" x1="0" x2="1000" y1="125" y2="125"></line>
                                <line stroke="#e5e7eb" stroke-width="1" x1="0" x2="1000" y1="200" y2="200"></line>
                                <line stroke="#e5e7eb" stroke-width="1" x1="0" x2="1000" y1="275" y2="275"></line>
                                
                                <!-- Views line -->
                                <path d="M0,250 C100,240 200,100 300,120 C400,140 500,80 600,60 C700,40 800,90 900,50 L1000,30" 
                                      fill="none" stroke="#1e3a8a" stroke-width="3"></path>
                                <path d="M0,250 C100,240 200,100 300,120 C400,140 500,80 600,60 C700,40 800,90 900,50 L1000,30 L1000,300 L0,300 Z" 
                                      fill="#1e3a8a" fill-opacity="0.05"></path>
                                
                                <!-- Inquiries line -->
                                <path d="M0,280 C100,270 200,220 300,230 C400,240 500,200 600,190 C700,180 800,210 900,180 L1000,170" 
                                      fill="none" stroke="#f59e0b" stroke-width="3"></path>
                            </svg>
                            
                            <!-- X-axis labels -->
                            <div class="absolute bottom-2 w-full flex justify-between px-4 text-xs text-gray-400">
                                <span>Day 1</span>
                                <span>Day 7</span>
                                <span>Day 14</span>
                                <span>Day 21</span>
                                <span>Day 30</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Donut Chart: Traffic Source -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col">
                        <h3 class="text-lg font-bold text-slate-900 mb-6">Traffic Source</h3>
                        
                        <div class="flex-1 flex flex-col items-center justify-center gap-6">
                            <!-- Donut Chart -->
                            <div class="relative size-48 rounded-full flex items-center justify-center" 
                                 style="background: conic-gradient(#1e3a8a 0% 80%, #f3f4f6 80% 100%);">
                                <div class="size-32 bg-white rounded-full flex flex-col items-center justify-center shadow-inner">
                                    <span class="text-3xl font-bold text-slate-900">80%</span>
                                    <span class="text-xs text-gray-500 uppercase font-medium">Mobile</span>
                                </div>
                            </div>
                            
                            <!-- Legend -->
                            <div class="w-full flex justify-center gap-8">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded bg-royal-blue"></span>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-900">Mobile</span>
                                        <span class="text-xs text-gray-500">80%</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded bg-gray-200"></span>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-900">Desktop</span>
                                        <span class="text-xs text-gray-500">20%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Popular Listings Table -->
                <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-slate-900">Most Popular Listings</h2>
                    </div>
                    <div class="overflow-x-auto w-full">
                        <table class="w-full min-w-[700px]">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-1/3">Property</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Views</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Leads Generated</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @php
                                    $popularListings = [
                                        [
                                            'id' => 1,
                                            'title' => 'Luxury Villa in Palms',
                                            'property_id' => 'LV-2045',
                                            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCToc03ewI-R-MLH1VeaILvpzcsPrzcNl35tCllapTZwSgSR39FEB-O03otqjWOPaQcd-FItQ4ORhThF5Ph3HmSpDPRgp1FgiERkSyWa_HVyO0UAkX8ApEuSzr8Z15ELVzKGK2pqUeHYTW4Ar_ZjAVyN-hy7GRG9SX86kKSlbXaRaHpijSfGxAa_XmtxQxozG8aaQRu7OlewhaXfNoZLh9hcU0aPLn-Us23Btb3P7qcH_zGOl8RrHEakkzwn2n7KGBDwjm-oBB_f70f',
                                            'status' => 'available',
                                            'views' => '1,200',
                                            'leads' => '15'
                                        ],
                                        [
                                            'id' => 2,
                                            'title' => 'Downtown Apartment 3BHK',
                                            'property_id' => 'DA-1102',
                                            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDdhjV4Kk0pc9BPnmOBfpnmUfWfZXgnJiKxKd0fBo3FkvF2Dw1_m-VUSfwR9RXPR9Mh_K6UP3m5MdbMtnevhMoJrYW9VcXQ0KkJcc3q3jt0T-8ADSIPTYb-T_SxL4I8HInHQ0ngU4p9h80Do3Ac7mXpX37jGdGqg4KkyMS2oZunrbaz8rrealQBNZd2sfkiIoKIxqvnAwuxwKG3267dMdaaTT_4aAlFfUdds9vqolu76zP8xpGfu04YE81DWahgI_ejytJbVjAk6OzQ',
                                            'status' => 'sold',
                                            'views' => '850',
                                            'leads' => '8'
                                        ],
                                        [
                                            'id' => 3,
                                            'title' => 'Commercial Space - Hub',
                                            'property_id' => 'CS-994',
                                            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDZQ1h5gajMn8U5gGwfOrWCKVWL7VHXHJMq0MaaOt3B4MeV4ipBbWSs9RTsUuAI1-ham_5nr2n1CCLr8y2VytmK2bnMk6UtMpwgnaKYudsto8BfD0Rw-lthz_UV9QwKDGCl-XQvQdaG7SRB1m0buhSHP-2QwyGiNpYEPuM4DQT1n2Bl38LpdnTnI93zhCbHG3wdijJZHS5w6wGDhSFixvqA-BUADXJAXbuAgzh5Zh9TEcNynTBErL21Wg0ZtPi4yxDvhU2UhSbeOUWo',
                                            'status' => 'available',
                                            'views' => '620',
                                            'leads' => '4'
                                        ]
                                    ];
                                @endphp
                                
                                @foreach($popularListings as $listing)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="h-12 w-20 bg-gray-200 rounded-md bg-cover bg-center shrink-0" 
                                                     style="background-image: url('{{ $listing['image'] }}')"></div>
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-bold text-slate-900">{{ $listing['title'] }}</span>
                                                    <span class="text-xs text-gray-500">ID: #{{ $listing['property_id'] }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $listing['status'] === 'available' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($listing['status']) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-600 font-medium">{{ $listing['views'] }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-bold text-royal-blue">{{ $listing['leads'] }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <button class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-amber-500 text-amber-600 hover:bg-amber-50 text-xs font-bold uppercase transition-colors">
                                                <span class="material-symbols-outlined text-[16px]">bolt</span>
                                                Boost
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
@endsection