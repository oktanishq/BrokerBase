<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<title>@yield('title', 'BrokerBase - Dealer Dashboard')</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@vite('resources/css/app.css')
@yield('head')
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-slate-900 overflow-hidden">
@if (!auth()->check())
    <script>window.location.href = '/admin/login';</script>
@endif
<div class="flex h-screen w-full bg-background-light" x-data="adminLayoutData()">
<x-sidebar />
<div class="flex flex-col flex-1 h-full lg:ml-64 relative overflow-hidden bg-gray-50">
<header class="flex items-center justify-between bg-white border-b border-gray-100 px-6 py-4 shadow-sm sticky top-0 z-20">
<div class="flex items-center gap-4">
<button class="lg:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-full">
<span class="material-symbols-outlined">menu</span>
</button>
<div class="flex flex-col">
@yield('header-content')
</div>
</div>
<div class="flex items-center gap-4">
<button class="flex items-center justify-center size-10 rounded-full bg-gray-50 hover:bg-gray-200 text-slate-700 transition-colors relative">
<span class="material-symbols-outlined text-[20px]">notifications</span>
<span class="absolute top-2 right-2 size-2 bg-red-500 rounded-full border-2 border-white"></span>
</button>
<div class="bg-center bg-no-repeat bg-cover rounded-full size-10 ring-2 ring-gray-100 cursor-pointer" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDjlFF_nSTOQN2xN5XEhoei2r1xmo6006_o8UoGMAFUfEAomAjyJR-_bXnIPonwd3cqDG7sOU8o_DGuG6ynBK32KcH-lRZpx1OAvvrV7EALzre8oOHD4wHQDNcs1u-RqUpqp6rABg-PLwMMJpYI1mwd0rmsHsf0SI7DMC0X71sycCni1WxVUk61lnXtb-Wzonan3tvT7xcDV3vnvIuNyz4n4mt6oBDAaqb4Ch5zP_c1FPKCfCmqMwaC598j6zQlRK21aawjBmED-Tjo');"></div>
</div>
</header>
<main class="flex-1 overflow-y-auto p-4 md:p-8">
<div class="max-w-[1400px] mx-auto flex flex-col gap-6">
@yield('content')
</div>
</main>
</div>
</div>
<x-logout-confirmation-modal />
<x-delete-confirmation-modal />

{{-- Alpine.js Admin Layout Data --}}
<script>
// Global state object
window.logoutModalState = {
    showLogoutModal: false,
    isLoggingOut: false
};

// Delete property modal state
window.deletePropertyModalState = {
    showDeleteModal: false,
    propertyToDelete: null,
    isDeleting: false
};

// Global functions
window.openLogoutModal = function() {
    window.logoutModalState.showLogoutModal = true;
};

window.closeLogoutModal = function() {
    window.logoutModalState.showLogoutModal = false;
};

window.confirmLogout = function() {
    window.logoutModalState.isLoggingOut = true;
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // No initialization needed - modal handles its own sync

    // Check authentication state periodically
    setInterval(checkAuthentication, 60000); // Check every minute
});

function checkAuthentication() {
    fetch('/admin/dashboard', { method: 'HEAD', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => {
            if (response.redirected && response.url.includes('/admin/login')) {
                window.location.href = '/admin/login';
            }
        })
        .catch(() => {
            // If fetch fails, assume not authenticated
            window.location.href = '/admin/login';
        });
}

function adminLayoutData() {
    return {
        // Simple Alpine.js data
        showLogoutModal: false,
        isLoggingOut: false,

        // Sync with global state
        init() {
            this.showLogoutModal = window.logoutModalState.showLogoutModal;
            this.isLoggingOut = window.logoutModalState.isLoggingOut;

            // Watch for changes and update global state
            this.$watch('showLogoutModal', (value) => {
                window.logoutModalState.showLogoutModal = value;
            });

            this.$watch('isLoggingOut', (value) => {
                window.logoutModalState.isLoggingOut = value;
            });
        }
    }
}
</script>
@yield('scripts')
</body>
</html>