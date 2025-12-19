<!-- Add New Property Modal -->
<div x-data="{ open: false }" @keydown.escape.window="open = false">
    <!-- Modal Trigger Button -->
    <button @click="open = true" class="flex items-center justify-center gap-2 rounded-full h-11 px-6 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-lg shadow-blue-200 transition-all transform hover:scale-105">
        <span class="material-symbols-outlined text-[20px]">add</span>
        <span>Add New Property</span>
    </button>

    <!-- Modal Overlay -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        
        <!-- Background Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="open = false"></div>
        
        <!-- Modal Content -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative w-full max-w-2xl bg-white rounded-xl shadow-xl transform transition-all"
                 style="display: none;">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Add New Property</h3>
                    <button @click="open = false" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                        <span class="material-symbols-outlined text-gray-400">close</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <form class="space-y-6">
                        <!-- Step Indicator -->
                        <div class="flex items-center justify-center space-x-4 mb-8">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                                <span class="ml-2 text-sm font-medium text-blue-600">Basics</span>
                            </div>
                            <div class="w-8 h-px bg-gray-300"></div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-medium">2</div>
                                <span class="ml-2 text-sm text-gray-500">Location</span>
                            </div>
                            <div class="w-8 h-px bg-gray-300"></div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-medium">3</div>
                                <span class="ml-2 text-sm text-gray-500">Media</span>
                            </div>
                            <div class="w-8 h-px bg-gray-300"></div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-medium">4</div>
                                <span class="ml-2 text-sm text-gray-500">Private</span>
                            </div>
                        </div>

                        <!-- Step 1: Basics -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-gray-900">Property Details</h4>
                            
                            <!-- Property Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Property Type</label>
                                <select class="w-full py-3 px-4 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option>Select Type</option>
                                    <option>Apartment</option>
                                    <option>Villa</option>
                                    <option>House</option>
                                    <option>Office</option>
                                    <option>Commercial</option>
                                </select>
                            </div>

                            <!-- Title -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Property Title</label>
                                <input type="text" 
                                       class="w-full py-3 px-4 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="e.g., Luxury 3BHK in Downtown">
                            </div>

                            <!-- Price and Configuration Row -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Price -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                                    <input type="text" 
                                           class="w-full py-3 px-4 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="$ 450,000">
                                </div>

                                <!-- Bedrooms -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Bedrooms</label>
                                    <select class="w-full py-3 px-4 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option>Select</option>
                                        <option>Studio</option>
                                        <option>1 BHK</option>
                                        <option>2 BHK</option>
                                        <option>3 BHK</option>
                                        <option>4 BHK</option>
                                        <option>5+ BHK</option>
                                    </select>
                                </div>

                                <!-- Bathrooms -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Bathrooms</label>
                                    <select class="w-full py-3 px-4 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option>Select</option>
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5+</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Area -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Carpet Area (sqft)</label>
                                <input type="number" 
                                       class="w-full py-3 px-4 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="1500">
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select class="w-full py-3 px-4 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option>Available</option>
                                    <option>Under Negotiation</option>
                                    <option>Sold</option>
                                </select>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                            <button type="button" 
                                    @click="open = false"
                                    class="px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors">
                                Cancel
                            </button>
                            <button type="button" 
                                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                Next: Location →
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>