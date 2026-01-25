<!-- Step 3: Media Gallery -->
<div class="p-6 md:p-8 space-y-6" @if($currentStep !== 2) style="display: none;" @endif>
    <div class="flex items-center gap-2 mb-2">
        <div class="size-6 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-xs font-bold">3</div>
        <h3 class="text-lg font-bold text-slate-900">Media Gallery</h3>
    </div>

    <div x-data="{
             isDragging: false,
             handleDrop(event) {
                 event.preventDefault();
                 this.isDragging = false;

                 const files = Array.from(event.dataTransfer.files).filter(file =>
                     file.type.startsWith('image/')
                 );

                 if (files.length > 0) {
                     // Create DataTransfer and set files on the input
                     const dt = new DataTransfer();
                     files.forEach(file => dt.items.add(file));

                     const input = document.getElementById('images');
                     input.files = dt.files;
                     input.dispatchEvent(new Event('change', { bubbles: true }));
                 }
             },
             handleDragOver(event) {
                 event.preventDefault();
             },
             handleDragEnter(event) {
                 event.preventDefault();
                 this.isDragging = true;
             },
             handleDragLeave(event) {
                 event.preventDefault();
                 this.isDragging = false;
             }
         }"
         @drop="handleDrop($event)"
         @dragover="handleDragOver($event)"
         @dragenter="handleDragEnter($event)"
         @dragleave="handleDragLeave($event)"
         :class="isDragging ? 'border-blue-400 bg-blue-100' : 'border-blue-200 bg-blue-50/50'"
         class="border-2 border-dashed rounded-xl p-8 flex flex-col items-center justify-center text-center cursor-pointer transition-colors">
        <div class="size-12 rounded-full bg-white text-royal-blue shadow-sm flex items-center justify-center mb-3">
            <span class="material-symbols-outlined text-3xl">cloud_upload</span>
        </div>
        <p class="text-royal-blue font-medium">Drag photos here or <label for="images" class="underline decoration-amber-500 decoration-2 underline-offset-2 cursor-pointer">Browse</label></p>
        <p class="text-xs text-gray-500 mt-1">Supported formats: JPG, PNG, WEBP</p>
        <input wire:model="newImages"
                id="images"
                type="file"
                multiple
                accept="image/*"
                class="hidden">
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
        @foreach($images as $index => $image)
            <div class="relative aspect-video rounded-lg overflow-hidden border border-gray-200 group">
                <img src="{{ $image->temporaryUrl() }}"
                     alt="Property {{ $index + 1 }}"
                     class="w-full h-full object-cover">
                @if($index === 0)
                    <div class="absolute top-2 left-2 bg-royal-blue text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">
                        Cover Image
                    </div>
                @endif
                <button wire:click="removeImage({{ $index }})"
                        type="button"
                        class="absolute top-2 right-2 bg-white/90 p-1 rounded hover:text-red-600 text-gray-500 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="material-symbols-outlined text-sm">delete</span>
                </button>
            </div>
        @endforeach
    </div>

    <div class="flex items-center justify-between py-3 px-4 bg-gray-50 rounded-lg border border-gray-200">
        <div class="flex flex-col">
            <span class="text-sm font-bold text-gray-900">Watermark Photos</span>
            <span class="text-xs text-gray-500">Apply Dealer Logo Watermark automatically?</span>
        </div>
        <label class="relative inline-flex items-center cursor-pointer">
            <input wire:model="watermark"
                   class="sr-only peer"
                   type="checkbox"
                   value="true">
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
        </label>
    </div>
</div>