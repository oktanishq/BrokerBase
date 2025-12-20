{{-- Logout Confirmation Modal Component --}}
<div
    data-modal="logout"
    x-data="{
        showLogoutModal: window.logoutModalState.showLogoutModal,
        isLoggingOut: window.logoutModalState.isLoggingOut,
        
        // Watch for global state changes
        init() {
            this.$watch('showLogoutModal', (value) => {
                window.logoutModalState.showLogoutModal = value;
            });
            
            this.$watch('isLoggingOut', (value) => {
                window.logoutModalState.isLoggingOut = value;
            });
            
            // Sync with global state periodically
            setInterval(() => {
                this.showLogoutModal = window.logoutModalState.showLogoutModal;
                this.isLoggingOut = window.logoutModalState.isLoggingOut;
            }, 100);
        }
    }"
    x-show="showLogoutModal"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div
            aria-hidden="true"
            class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"
            @click="showLogoutModal = false"
        ></div>
        <span aria-hidden="true" class="hidden sm:inline-block sm:align-middle sm:h-screen">​</span>
        
        {{-- Modal Container --}}
        <div
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
        >
            {{-- Modal Header & Content --}}
            <div class="bg-white px-4 pt-5 pb-4 sm:p-8 sm:pb-6">
                <div class="sm:flex sm:items-start">
                    {{-- Icon --}}
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <span class="material-symbols-outlined text-red-600">logout</span>
                    </div>
                    
                    {{-- Text Content --}}
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title">
                            Are you sure you want to log out?
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                You will need to enter your username and password to access your account again.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Modal Footer --}}
            <div class="bg-gray-50 px-4 py-4 sm:px-8 sm:flex sm:flex-row-reverse gap-3">
                {{-- Logout Button (Form) --}}
                <form method="POST" action="/logout" class="w-full sm:w-auto">
                    @csrf
                    <button
                        type="submit"
                        @click="isLoggingOut = true; document.body.style.overflow = 'auto';"
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-5 py-2.5 bg-red-600 text-base font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors"
                    >
                        <span x-show="!isLoggingOut">Logout</span>
                        <span x-show="isLoggingOut" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Logging out...
                        </span>
                    </button>
                </form>
                
                {{-- Cancel Button --}}
                <button
                    type="button"
                    @click="showLogoutModal = false; document.body.style.overflow = 'auto';"
                    class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-royal-blue sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>