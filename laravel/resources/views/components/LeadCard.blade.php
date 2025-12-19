@props(['lead'])

<div class="p-4 bg-white flex flex-col gap-4">
    <div class="flex justify-between items-start">
        <div>
            <h3 class="font-bold text-slate-900">{{ $lead['name'] }}</h3>
            <p class="text-xs text-gray-500">{{ $lead['phone'] }}</p>
        </div>
        <x-status-badge :status="$lead['status']" :animated="$lead['status'] === 'new'" />
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