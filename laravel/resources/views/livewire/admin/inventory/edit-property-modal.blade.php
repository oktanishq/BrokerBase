<!-- Edit Property Modal -->
<div x-data
     x-show="$wire.isOpen"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
     style="display: none;"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <!-- Background Overlay -->
    <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity"
         @click="$wire.closeModal()"></div>

    <!-- Modal Content -->
    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl transform transition-all flex flex-col max-h-[90vh] overflow-hidden">

        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-bold text-slate-900">Edit Listing</h2>
            <button @click="$wire.closeModal()"
                    class="text-gray-400 hover:text-gray-600 transition-colors rounded-full p-1 hover:bg-gray-100">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="overflow-y-auto p-6 space-y-6 flex-1">

            <!-- Property Preview Section -->
            @if($property)
                <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <div class="w-16 h-12 bg-cover bg-center rounded-md shrink-0"
                         style="background-image: url('{{ $property['image'] ?? '/images/placeholder-property.jpg' }}')"></div>
                    <div>
                        <h3 class="font-bold text-slate-800">{{ $property['title'] ?? 'Loading...' }}</h3>
                        <p class="text-xs text-gray-500 mt-1">ID: #{{ $property['id'] ?? 'N/A' }} • Added recently</p>
                    </div>
                </div>
            @endif

            <!-- Status and Labels Section -->
            <div class="space-y-4">
                <!-- Listing Status -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Listing Status</label>
                    <div class="flex bg-gray-100 p-1 rounded-lg">
                        <button wire:click="$set('status', 'available')"
                                :class="$status === 'available' ? 'bg-royal-blue text-white shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                class="flex-1 py-1.5 px-3 rounded-md text-sm font-medium transition-all">
                            Available
                        </button>
                        <button wire:click="$set('status', 'sold')"
                                :class="$status === 'sold' ? 'bg-royal-blue text-white shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                class="flex-1 py-1.5 px-3 rounded-md text-sm font-medium transition-all">
                            Sold
                        </button>
                        <button wire:click="$set('status', 'draft')"
                                :class="$status === 'draft' ? 'bg-royal-blue text-white shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                class="flex-1 py-1.5 px-3 rounded-md text-sm font-medium transition-all">
                            Draft
                        </button>
                    </div>
                </div>

                <!-- Public Badge and Featured Toggle -->
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Public Badge</label>
                        <div class="flex flex-wrap gap-2">
                            <button wire:click="$set('label_type', 'none')"
                                    :class="$label_type === 'none' ? 'border-amber-500 bg-amber-50 text-amber-700' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'"
                                    class="px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors">
                                None
                            </button>
                            <button wire:click="$set('label_type', 'new')"
                                    :class="$label_type === 'new' ? 'border-amber-500 bg-amber-50 text-amber-700' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'"
                                    class="px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors">
                                New Arrival
                            </button>
                            <button wire:click="$set('label_type', 'popular')"
                                    :class="$label_type === 'popular' ? 'border-amber-500 bg-amber-50 text-amber-700' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'"
                                    class="px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors">
                                Popular
                            </button>
                            <button wire:click="$set('label_type', 'verified')"
                                    :class="$label_type === 'verified' ? 'border-amber-500 bg-amber-50 text-amber-700' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'"
                                    class="px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors">
                                Verified
                            </button>
                        </div>
                    </div>
                    <div class="flex items-end pb-1">
                        <label class="inline-flex items-center cursor-pointer gap-3">
                            <span class="text-sm font-medium text-slate-700">Featured Property</span>
                            <div class="relative">
                                <input wire:model.live="is_featured"
                                       type="checkbox"
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-royal-blue"></div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Modify All Details Button -->
            <div class="pt-2">
                <button wire:click="editAllDetails"
                        class="w-full py-2.5 px-4 rounded-lg border border-royal-blue/30 text-royal-blue hover:bg-blue-50 font-medium text-sm flex items-center justify-center gap-2 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                    Modify All Details & Media
                </button>
            </div>

            <!-- Owner & Private Information Section -->
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <div class="bg-amber-50/50 px-4 py-3 border-b border-amber-100/50 flex items-center justify-between cursor-pointer"
                     @click="$wire.toggleOwnerSection()">
                    <div class="flex items-center gap-2 text-amber-900/80">
                        <span class="material-symbols-outlined text-[20px]">lock</span>
                        <h4 class="font-semibold text-sm">Owner & Private Information</h4>
                    </div>
                    <span class="material-symbols-outlined text-gray-400 text-[20px] transition-transform"
                          :class="$wire.ownerSectionExpanded ? 'rotate-180' : ''">expand_less</span>
                </div>

                <div x-show="$wire.ownerSectionExpanded"
                     x-transition
                     class="p-4 bg-white grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500">Owner Name</label>
                        <input wire:model.live="owner_name"
                               type="text"
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none"
                               placeholder="Enter owner name">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500">Owner Phone</label>
                        <input wire:model.live="owner_phone"
                               type="text"
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none"
                               placeholder="Enter owner phone">
                    </div>
                    <div class="space-y-1 md:col-span-2">
                        <label class="text-xs font-medium text-gray-500">Net Price (Private)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-400">$</span>
                            <input wire:model.live="net_price"
                                   type="text"
                                   class="w-full pl-7 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none font-mono"
                                   placeholder="420,000">
                        </div>
                    </div>
                    <div class="space-y-1 md:col-span-2">
                        <label class="text-xs font-medium text-gray-500">Private Notes</label>
                        <textarea wire:model.live="private_notes"
                                  rows="3"
                                  class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-royal-blue focus:border-royal-blue outline-none resize-none"
                                  placeholder="Add internal notes here..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-6 pt-2 border-t border-gray-50 flex items-center justify-end gap-3 bg-white">
            <button @click="$wire.closeModal()"
                    class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">
                Discard Changes
            </button>
            <button wire:click="saveChanges"
                    wire:loading.attr="disabled"
                    class="px-5 py-2.5 rounded-lg text-sm font-bold text-white bg-royal-blue hover:bg-blue-800 shadow-lg shadow-blue-900/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove>Update Listing</span>
                <span wire:loading class="flex items-center gap-2">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                    Saving...
                </span>
            </button>
        </div>
    </div>
</div>