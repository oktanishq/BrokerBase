@props(['currentPropertyId'])

@php
$similarProperties = \App\Models\Property::where('id', '!=', $currentPropertyId)
    ->inRandomOrder()
    ->limit(5)
    ->get();
@endphp

@if($similarProperties->count() > 0)
<div class="mt-12 mb-8 px-4 sm:px-6 lg:px-10">
    <h3 class="text-gray-900 dark:text-white text-2xl font-bold mb-6">You May Also Like</h3>
    <div class="overflow-x-auto no-scrollbar">
        <div class="flex gap-6 pb-4 min-w-max">
            @foreach($similarProperties as $property)
            <a href="{{ route('property.show', $property->id) }}" class="group block flex-shrink-0 w-72">
                <div class="bg-white dark:bg-background-dark rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-800">
                    <div class="aspect-[4/3] overflow-hidden bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                        @if($property->primary_image_url)
                        <img src="{{ $property->primary_image_url }}" alt="{{ $property->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                        <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                            <span class="material-symbols-outlined text-4xl mb-2">image</span>
                            <span class="text-sm">No image</span>
                        </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h4 class="text-gray-900 dark:text-white font-semibold text-lg mb-2 line-clamp-2">{{ $property->name }}</h4>
                        <p class="text-gold font-bold text-xl">{{ $property->price > 0 ? '₹ ' . number_format($property->price) : '₹ TBD' }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif