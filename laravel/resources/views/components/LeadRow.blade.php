@props(['lead'])

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
        <x-status-badge :status="$lead['status']" :animated="$lead['status'] === 'new'" />
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right">
        <x-lead-actions :lead-id="$lead['id']" />
    </td>
</tr>