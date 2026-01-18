<!-- Coming Soon Modal -->
<div x-data="{ showModal: true }" x-show="showModal" x-init="$nextTick(() => { showModal = true })"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
     style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-8 text-center relative overflow-hidden">

        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 left-0 w-32 h-32 bg-royal-blue rounded-full -translate-x-16 -translate-y-16"></div>
            <div class="absolute bottom-0 right-0 w-24 h-24 bg-amber-500 rounded-full translate-x-12 translate-y-12"></div>
        </div>

        <!-- Close Button -->
        <button @click="showModal = false"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
            <span class="material-symbols-outlined text-2xl">close</span>
        </button>

        <!-- Icon -->
        <div class="relative z-10 mb-6">
            <div class="w-20 h-20 bg-gradient-to-br from-royal-blue to-blue-600 rounded-full flex items-center justify-center mx-auto shadow-lg">
                <span class="material-symbols-outlined text-white text-3xl">rocket_launch</span>
            </div>
        </div>

        <!-- Content -->
        <div class="relative z-10">
            <h2 class="text-2xl font-bold text-slate-900 mb-3">Coming Soon</h2>
            <p class="text-gray-600 mb-6 leading-relaxed">
                We're working hard to bring you amazing new features. This page will be available in an upcoming update.
            </p>

            <!-- Progress Indicator -->
            <div class="mb-6">
                <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                    <div class="bg-gradient-to-r from-royal-blue to-amber-500 h-2 rounded-full animate-pulse"
                         style="width: 65%"></div>
                </div>
                <p class="text-sm text-gray-500">65% Complete</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 justify-center">
                <button @click="showModal = false"
                        class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Got it
                </button>
                <a href="{{ url('/admin/dashboard') }}"
                   class="px-6 py-2 bg-royal-blue text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>