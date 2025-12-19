@props(['title', 'centerValue', 'centerLabel', 'segments' => []])

<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col">
    <h3 class="text-lg font-bold text-slate-900 mb-6">{{ $title }}</h3>
    
    <div class="flex-1 flex flex-col items-center justify-center gap-6">
        <!-- Donut Chart -->
        <div class="relative size-48 rounded-full flex items-center justify-center" 
             style="background: conic-gradient(#1e3a8a 0% 80%, #f3f4f6 80% 100%);">
            <div class="size-32 bg-white rounded-full flex flex-col items-center justify-center shadow-inner">
                <span class="text-3xl font-bold text-slate-900">{{ $centerValue }}</span>
                <span class="text-xs text-gray-500 uppercase font-medium">{{ $centerLabel }}</span>
            </div>
        </div>
        
        <!-- Legend -->
        @if(count($segments) > 0)
            <div class="w-full flex justify-center gap-8">
                @foreach($segments as $segment)
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded {{ $segment['color'] }}"></span>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-900">{{ $segment['label'] }}</span>
                            <span class="text-xs text-gray-500">{{ $segment['value'] }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Default legend if no segments provided -->
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
        @endif
    </div>
</div>