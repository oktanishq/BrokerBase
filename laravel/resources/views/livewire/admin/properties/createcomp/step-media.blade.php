<!-- Step 3: Media Gallery -->
<div class="p-6 md:p-8 space-y-6" @if($currentStep !== 2) style="display: none;" @endif>
    <div class="flex items-center gap-2 mb-2">
        <div class="size-6 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-xs font-bold">3</div>
        <h3 class="text-lg font-bold text-slate-900">Media Gallery</h3>
    </div>

    <!-- Drag & Drop Upload Area -->
    <div x-data="{
             isDragging: false,
             handleDrop(event) {
                 event.preventDefault();
                 this.isDragging = false;

                 const files = Array.from(event.dataTransfer.files).filter(file =>
                     file.type.startsWith('image/')
                 );

                 if (files.length > 0) {
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

    <!-- Sortable Image Grid -->
    <div x-data="{
            sortable: null,
            init() {
                this.initSortable();
                this.$watch('images', () => {
                    this.$nextTick(() => this.initSortable());
                });
            },
            initSortable() {
                if (this.sortable) {
                    this.sortable.destroy();
                }
                this.sortable = new Sortable(this.$el, {
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    filter: '.ignore-drag',
                    onEnd: (evt) => {
                        const newOrder = [];
                        this.$el.querySelectorAll('[data-image-index]').forEach(el => {
                            newOrder.push(parseInt(el.dataset.imageIndex));
                        });
                        @this.reorderImages(newOrder);
                    }
                });
            }
        }"
        class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        @foreach($images as $index => $image)
            <div class="relative group rounded-lg overflow-hidden bg-gray-100 cursor-grab active:cursor-grabbing touch-none"
                 data-image-index="{{ $index }}"
                 x-data="{ 
                     isPrimary: {{ $index === $primaryImageIndex ? 'true' : 'false' }},
                     pendingDelete: false,
                     startDeleteTimer() {
                         this.pendingDelete = true;
                         this.$refs.deleteBtn.title = 'Double-click to confirm delete';
                     },
                     cancelDeleteTimer() {
                         this.pendingDelete = false;
                         this.$refs.deleteBtn.title = 'Delete image';
                     }
                 }"
                 @mouseleave="cancelDeleteTimer()">
                
                <!-- Image Container - 4:5 Ratio with object-cover -->
                <div class="aspect-[4/5] w-full relative">
                    <img src="{{ $image->temporaryUrl() }}"
                         alt="Property Image {{ $index + 1 }}"
                         class="w-full h-full object-cover">
                    
                    <!-- Primary Badge -->
                    <div x-show="isPrimary"
                         x-transition
                         class="absolute top-2 left-2 bg-royal-blue text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">
                        <span class="material-symbols-outlined text-xs mr-0.5">star</span>
                        Primary
                    </div>
                </div>

                <!-- Delete Button -->
                <button wire:click="removeImage({{ $index }})"
                        type="button"
                        x-ref="deleteBtn"
                        class="ignore-drag absolute top-2 right-2 bg-white/90 p-1 rounded hover:text-red-600 text-gray-500 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity"
                        title="Double-click to delete"
                        @dblclick="$wire.removeImage({{ $index }})">
                    <span class="material-symbols-outlined text-sm">delete</span>
                </button>

                <!-- Hover Overlay with Actions -->
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors pointer-events-none"></div>

                <!-- Bottom Actions - Primary Selection -->
                <div class="ignore-drag absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                    <label class="flex items-center justify-center gap-2 cursor-pointer">
                        <input type="radio"
                               name="primary_image_radio"
                               value="{{ $index }}"
                               x-model="isPrimary"
                               @change="@this.setPrimaryImage({{ $index }})"
                               class="w-4 h-4 text-royal-blue border-gray-300 focus:ring-royal-blue">
                        <span class="text-xs text-white font-medium drop-shadow">Set as Primary</span>
                    </label>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Empty State -->
    @if(empty($images))
        <div class="text-center py-8">
            <span class="material-symbols-outlined text-5xl text-gray-300">photo_library</span>
            <p class="text-gray-500 mt-2">No images uploaded yet</p>
            <p class="text-xs text-gray-400">Drag and drop or browse to add images</p>
        </div>
    @endif

    <!-- Image Order Info -->
    @if(count($images) > 1)
        <div class="flex items-center gap-2 text-xs text-gray-500 bg-gray-50 px-3 py-2 rounded-lg">
            <span class="material-symbols-outlined text-sm">info</span>
            <span>Drag images to reorder. Click "Set as Primary" to make an image the cover.</span>
        </div>
    @endif

    <div class="py-3 px-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg border border-amber-200">
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-start gap-3 flex-1 min-w-0">
                <div class="flex items-center justify-center w-8 h-8 bg-amber-100 rounded-full flex-shrink-0 mt-0.5">
                    <span class="material-symbols-outlined text-amber-600 text-sm">lock</span>
                </div>
                <div class="flex flex-col min-w-0 flex-1">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                        <span class="text-sm font-bold text-gray-900 truncate">Instagram & YouTube Ready Images</span>
                        <span class="px-2 py-0.5 bg-amber-500 text-white text-xs font-bold rounded-full self-start sm:self-auto whitespace-nowrap">Coming Soon</span>
                    </div>
                    <span class="text-xs text-gray-600 mt-1">Get professionally watermarked images optimized for social media platforms</span>
                </div>
            </div>
            <div class="text-amber-600 flex-shrink-0">
                <span class="material-symbols-outlined text-lg">arrow_forward</span>
            </div>
        </div>
    </div>
</div>

<style>
    /* SortableJS helper classes - single token names */
    .sortable-ghost {
        opacity: 0.4;
        background-color: #dbeafe !important;
    }
    .sortable-chosen {
        box-shadow: 0 0 0 2px #3b82f6;
    }
    .sortable-drag {
        opacity: 0.5;
    }
</style>
