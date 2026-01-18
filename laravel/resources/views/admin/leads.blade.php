@extends('layouts.admin')

@section('title', 'BrokerBase Dealer Leads')

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
                <span class="ml-1 font-medium text-gray-700">Leads</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
                
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4">
                    <div>
                        <h1 class="text-slate-900 text-3xl font-black leading-tight tracking-tight">Leads & Inquiries</h1>
                        <p class="text-gray-500 mt-1 text-sm">Manage incoming buyer interest and follow-ups.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a class="text-sm font-medium text-gray-500 hover:text-royal-blue flex items-center gap-1 transition-colors" href="#">
                            <span class="material-symbols-outlined text-[18px]">download</span>
                            Export CSV
                        </a>
                        <button class="flex items-center justify-center gap-2 rounded-full h-11 px-6 border-2 border-blue-600 text-blue-600 hover:bg-blue-50 text-sm font-bold transition-all">
                            <span class="material-symbols-outlined text-[20px]">add</span>
                            <span>Add Manual Lead</span>
                        </button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between h-32">
                        <div class="flex justify-between items-start">
                            <h3 class="text-gray-500 font-medium text-sm">New Leads (Today)</h3>
                            <span class="bg-amber-100 text-amber-700 p-1.5 rounded-lg">
                                <span class="material-symbols-outlined text-[20px]">new_releases</span>
                            </span>
                        </div>
                        <div class="text-4xl font-bold text-amber-500">4</div>
                    </div>
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between h-32">
                        <div class="flex justify-between items-start">
                            <h3 class="text-gray-500 font-medium text-sm">Pending Follow-ups</h3>
                            <span class="bg-blue-50 text-blue-600 p-1.5 rounded-lg">
                                <span class="material-symbols-outlined text-[20px]">schedule</span>
                            </span>
                        </div>
                        <div class="text-4xl font-bold text-blue-600">12</div>
                    </div>
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between h-32">
                        <div class="flex justify-between items-start">
                            <h3 class="text-gray-500 font-medium text-sm">Conversion Rate</h3>
                            <span class="bg-green-50 text-green-600 p-1.5 rounded-lg">
                                <span class="material-symbols-outlined text-[20px]">trending_up</span>
                            </span>
                        </div>
                        <div class="text-4xl font-bold text-green-600">15%</div>
                    </div>
                </div>

                <!-- Leads Table -->
                <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                    
                    <!-- Desktop Table -->
                    <table class="w-full hidden md:table">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lead Name & Contact</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Interest (Property)</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Source</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php
                                $sampleLeads = [
                                    [
                                        'id' => 1,
                                        'name' => 'John Doe',
                                        'phone' => '+1 (555) 123-4567',
                                        'property_title' => 'Luxury Villa',
                                        'property_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCToc03ewI-R-MLH1VeaILvpzcsPrzcNl35tCllapTZwSgSR39FEB-O03otqjWOPaQcd-FItQ4ORhThF5Ph3HmSpDPRgp1FgiERkSyWa_HVyO0UAkX8ApEuSzr8Z15ELVzKGK2pqUeHYTW4Ar_ZjAVyN-hy7GRG9SX86kKSlbXaRaHpijSfGxAa_XmtxQxozG8aaQRu7OlewhaXfNoZLh9hcU0aPLn-Us23Btb3P7qcH_zGOl8RrHEakkzwn2n7KGBDwjm-oBB_f70f',
                                        'source' => 'Call Button',
                                        'source_icon' => 'call',
                                        'source_class' => 'bg-blue-100 text-blue-700',
                                        'date' => '2 hours ago',
                                        'status' => 'new'
                                    ],
                                    [
                                        'id' => 2,
                                        'name' => 'Sarah Smith',
                                        'phone' => '+1 (555) 987-6543',
                                        'property_title' => 'City Apt',
                                        'property_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDdhjV4Kk0pc9BPnmOBfpnmUfWfZXgnJiKxKd0fBo3FkvF2Dw1_m-VUSfwR9RXPR9Mh_K6UP3m5MdbMtnevhMoJrYW9VcXQ0KkJcc3q3jt0T-8ADSIPTYb-T_SxL4I8HInHQ0ngU4p9h80Do3Ac7mXpX37jGdGqg4KkyMS2oZunrbaz8rrealQBNZd2sfkiIoKIxqvnAwuxwKG3267dMdaaTT_4aAlFfUdds9vqolu76zP8xpGfu04YE81DWahgI_ejytJbVjAk6OzQ',
                                        'source' => 'WhatsApp Click',
                                        'source_icon' => 'chat',
                                        'source_class' => 'bg-green-100 text-green-700',
                                        'date' => '5 hours ago',
                                        'status' => 'contacted'
                                    ],
                                    [
                                        'id' => 3,
                                        'name' => 'Mike Ross',
                                        'phone' => '+1 (555) 444-1122',
                                        'property_title' => 'Commercial Space',
                                        'property_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDZQ1h5gajMn8U5gGwfOrWCKVWL7VHXHJMq0MaaOt3B4MeV4ipBbWSs9RTsUuAI1-ham_5nr2n1CCLr8y2VytmK2bnMk6UtMpwgnaKYudsto8BfD0Rw-lthz_UV9QwKDGCl-XQvQdaG7SRB1m0buhSHP-2QwyGiNpYEPuM4DQT1n2Bl38LpdnTnI93zhCbHG3wdijJZHS5w6wGDhSFixvqA-BUADXJAXbuAgzh5Zh9TEcNynTBErL21Wg0ZtPi4yxDvhU2UhSbeOUWo',
                                        'source' => 'Walk-in',
                                        'source_icon' => 'directions_walk',
                                        'source_class' => 'bg-gray-200 text-gray-700',
                                        'date' => '1 day ago',
                                        'status' => 'negotiation'
                                    ]
                                ];
                            @endphp
                            
                            @foreach($sampleLeads as $lead)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-900">{{ $lead['name'] }}</span>
                                            <span class="text-xs text-gray-500">{{ $lead['phone'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a class="flex items-center gap-3 group" href="#">
                                            <div class="h-10 w-16 bg-gray-200 rounded-md bg-cover bg-center" 
                                                 style="background-image: url('{{ $lead['property_image'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCToc03ewI-R-MLH1VeaILvpzcsPrzcNl35tCllapTZwSgSR39FEB-O03otqjWOPaQcd-FItQ4ORhThF5Ph3HmSpDPRgp1FgiERkSyWa_HVyO0UAkX8ApEuSzr8Z15ELVzKGK2pqUeHYTW4Ar_ZjAVyN-hy7GRG9SX86kKSlbXaRaHpijSfGxAa_XmtxQxozG8aaQRu7OlewhaXfNoZLh9hcU0aPLn-Us23Btb3P7qcH_zGOl8RrHEakkzwn2n7KGBDwjm-oBB_f70f' }}')">
                                            </div>
                                            <span class="text-sm font-medium text-royal-blue group-hover:underline">{{ $lead['property_title'] }}</span>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $lead['source_class'] }}">
                                            <span class="material-symbols-outlined text-[14px]">{{ $lead['source_icon'] }}</span>
                                            {{ $lead['source'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-500">{{ $lead['date'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="relative inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $lead['status'] === 'new' ? 'bg-amber-50 text-amber-700 border-amber-200' : ($lead['status'] === 'contacted' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-purple-50 text-purple-700 border-purple-200') }}">
                                            @if($lead['status'] === 'new')
                                                <span class="relative flex h-2 w-2">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                                </span>
                                            @endif
                                            {{ ucfirst($lead['status']) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button class="size-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-all" title="Call">
                                                <span class="material-symbols-outlined text-[18px]">call</span>
                                            </button>
                                            <button class="size-8 rounded-full bg-green-50 text-green-600 hover:bg-green-600 hover:text-white flex items-center justify-center transition-all" title="WhatsApp">
                                                <span class="material-symbols-outlined text-[18px]">chat</span>
                                            </button>
                                            <button class="size-8 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-600 hover:text-white flex items-center justify-center transition-all" title="Add Note">
                                                <span class="material-symbols-outlined text-[18px]">edit</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Mobile Cards -->
                    <div class="md:hidden flex flex-col divide-y divide-gray-100">
                        @foreach($sampleLeads as $lead)
                            <div class="p-4 bg-white flex flex-col gap-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-bold text-slate-900">{{ $lead['name'] }}</h3>
                                        <p class="text-xs text-gray-500">{{ $lead['phone'] }}</p>
                                    </div>
                                    <span class="relative inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $lead['status'] === 'new' ? 'bg-amber-50 text-amber-700 border-amber-200' : ($lead['status'] === 'contacted' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-purple-50 text-purple-700 border-purple-200') }}">
                                        @if($lead['status'] === 'new')
                                            <span class="relative flex h-1.5 w-1.5">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-amber-500"></span>
                                            </span>
                                        @endif
                                        {{ ucfirst($lead['status']) }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="h-12 w-16 bg-gray-200 rounded bg-cover bg-center shrink-0" 
                                         style="background-image: url('{{ $lead['property_image'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCToc03ewI-R-MLH1VeaILvpzcsPrzcNl35tCllapTZwSgSR39FEB-O03otqjWOPaQcd-FItQ4ORhThF5Ph3HmSpDPRgp1FgiERkSyWa_HVyO0UAkX8ApEuSzr8Z15ELVzKGK2pqUeHYTW4Ar_ZjAVyN-hy7GRG9SX86kKSlbXaRaHpijSfGxAa_XmtxQxozG8aaQRu7OlewhaXfNoZLh9hcU0aPLn-Us23Btb3P7qcH_zGOl8RrHEakkzwn2n7KGBDwjm-oBB_f70f' }}')">
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500 uppercase font-semibold">Interest</span>
                                        <span class="text-sm font-medium text-royal-blue">{{ $lead['property_title'] }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex gap-2">
                                    <button class="flex-1 h-10 flex items-center justify-center gap-2 bg-blue-600 text-white rounded-lg font-medium shadow-sm hover:bg-blue-700 active:scale-95 transition-all">
                                        <span class="material-symbols-outlined text-[18px]">call</span>
                                        Call
                                    </button>
                                    <button class="flex-1 h-10 flex items-center justify-center gap-2 bg-green-600 text-white rounded-lg font-medium shadow-sm hover:bg-green-700 active:scale-95 transition-all">
                                        <span class="material-symbols-outlined text-[18px]">chat</span>
                                        WhatsApp
                                    </button>
                                    <button class="size-10 flex items-center justify-center bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Coming Soon Modal -->
                <x-admin.coming-soon-modal />
@endsection