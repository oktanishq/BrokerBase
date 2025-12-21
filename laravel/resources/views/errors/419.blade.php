<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Session Expired - BrokerBase</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@vite('resources/css/app.css')
</head>
<body class="font-display antialiased">
<div class="relative flex min-h-screen w-full flex-col overflow-hidden bg-royal-blue dark:bg-royal-blue-dark">
<!-- Background Pattern/Gradient -->
<div class="absolute inset-0 bg-[url('https://placeholder.pics/svg/2000')] bg-cover bg-center opacity-10 mix-blend-overlay" data-alt="Subtle geometric lines pattern background"></div>
<div class="absolute inset-0 bg-gradient-to-br from-royal-blue via-royal-blue-dark to-black opacity-90"></div>
<div class="layout-container relative flex h-full grow flex-col items-center justify-center p-4 sm:p-8">
<!-- Central Card -->
<div class="w-full max-w-[520px] rounded-xl bg-white p-8 shadow-2xl ring-1 ring-white/10 sm:p-10 dark:bg-[#1a1a1a]" x-data="sessionExpiredData()">
<!-- Logo & Status Icon -->
<div class="mb-6 flex flex-col items-center text-center">
<div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-orange-100 text-orange-600 dark:bg-orange-900/20 dark:text-orange-400">
<span class="material-symbols-outlined text-[40px]">schedule</span>
</div>
<h1 class="text-2xl font-bold tracking-tight text-[#181511] dark:text-white">Session Expired</h1>
<p class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-400">Your session has timed out for security reasons</p>
</div>

<!-- Message Content -->
<div class="mb-8 text-center">
<p class="text-[#181511] dark:text-gray-200 mb-4">
Your login session has expired due to inactivity. This is a security measure to protect your account.
</p>
<p class="text-sm text-slate-600 dark:text-slate-400">
Please log in again to continue using BrokerBase.
</p>
</div>

<!-- Countdown Timer -->
<div class="mb-8 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg" x-show="showCountdown">
<div class="flex items-center justify-center gap-3">
<span class="material-symbols-outlined text-orange-500">timer</span>
<span class="text-sm font-medium text-[#181511] dark:text-gray-200">
Redirecting in <span class="font-bold text-orange-600 dark:text-orange-400" x-text="countdown">5</span> seconds...
</span>
</div>
</div>

<!-- Action Buttons -->
<div class="flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
<button 
@click="redirectNow" 
class="flex h-12 w-full items-center justify-center rounded-lg bg-primary-admin px-4 text-sm font-bold text-white transition-colors hover:bg-primary-admin-dark focus:outline-none focus:ring-2 focus:ring-primary-admin focus:ring-offset-2 dark:text-[#181511] sm:w-auto"
>
<span class="material-symbols-outlined mr-2">login</span>
Log In Now
</button>
<button 
@click="cancelRedirect" 
x-show="showCancel"
class="flex h-12 w-full items-center justify-center rounded-lg border border-gray-300 bg-white px-4 text-sm font-medium text-[#181511] transition-colors hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-admin focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 sm:w-auto"
>
<span class="material-symbols-outlined mr-2">cancel</span>
Cancel
</button>
</div>

<!-- Help Text -->
<div class="mt-6 text-center text-xs text-slate-500 dark:text-slate-400">
<p>If you're experiencing issues, please contact support.</p>
<p class="mt-1">© 2025 BrokerBase. All rights reserved.</p>
</div>
</div>
</div>
</div>

<script>
function sessionExpiredData() {
return {
countdown: 5,
showCountdown: true,
showCancel: true,
redirectUrl: '/admin/login',
autoRedirectTimer: null,

init() {
this.startCountdown();
},

startCountdown() {
this.autoRedirectTimer = setInterval(() => {
this.countdown--;
if (this.countdown <= 0) {
this.redirectNow();
}
}, 1000);
},

redirectNow() {
if (this.autoRedirectTimer) {
clearInterval(this.autoRedirectTimer);
}
window.location.href = this.redirectUrl;
},

cancelRedirect() {
if (this.autoRedirectTimer) {
clearInterval(this.autoRedirectTimer);
}
this.showCountdown = false;
this.showCancel = false;
this.countdown = 0;
}
}
}
</script>
</body>
</html>