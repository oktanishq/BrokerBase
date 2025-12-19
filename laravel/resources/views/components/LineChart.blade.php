@props(['title', 'legend' => []])

<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
    <div class="flex flex-col sm:flex-row items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-slate-900">{{ $title }}</h3>
        @if(count($legend) > 0)
            <div class="flex items-center gap-4 text-sm mt-2 sm:mt-0">
                @foreach($legend as $item)
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full {{ $item['color'] }}"></span>
                        <span class="text-gray-600">{{ $item['label'] }}</span>
                    </div>
                @endforeach
            </div>
        @endif
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