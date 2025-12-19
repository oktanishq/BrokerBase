<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>BrokerBase - Dealer Login</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
@vite('resources/css/app.css')
</head>
<body class="font-display antialiased">
<div class="relative flex min-h-screen w-full flex-col overflow-hidden bg-royal-blue dark:bg-royal-blue-dark">
<!-- Background Pattern/Gradient -->
<div class="absolute inset-0 bg-[url('https://placeholder.pics/svg/2000')] bg-cover bg-center opacity-10 mix-blend-overlay" data-alt="Subtle geometric lines pattern background"></div>
<div class="absolute inset-0 bg-gradient-to-br from-royal-blue via-royal-blue-dark to-black opacity-90"></div>
<div class="layout-container relative flex h-full grow flex-col items-center justify-center p-4 sm:p-8">
<!-- Central Card -->
<div class="w-full max-w-[480px] rounded-xl bg-white p-8 shadow-2xl ring-1 ring-white/10 sm:p-10 dark:bg-[#1a1a1a]">
<!-- Logo & Tagline -->
<div class="mb-8 flex flex-col items-center text-center">
<div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-royal-blue/10 text-primary-admin">
<span class="material-symbols-outlined text-[32px]">apartment</span>
</div>
<h1 class="text-2xl font-bold tracking-tight text-[#181511] dark:text-white">BrokerBase</h1>
<p class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-400">The Digital Vault for Modern Real Estate Dealers</p>
</div>
<!-- Login Form -->
<form action="#" class="flex flex-col gap-6" method="POST" onsubmit="event.preventDefault();">
<!-- Email Field -->
<div class="flex flex-col gap-2">
<label class="text-sm font-medium leading-none text-[#181511] dark:text-gray-200" for="email">
Email Address
</label>
<div class="relative">
<input class="flex h-12 w-full rounded-lg border border-[#e6e2db] bg-white px-3 py-2 text-sm text-[#181511] placeholder:text-[#8a7b60]/60 focus:border-primary-admin focus:outline-none focus:ring-1 focus:ring-primary-admin dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500" id="email" name="email" placeholder="dealer@agency.com" required="" type="email"/>
<div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
<span class="material-symbols-outlined text-[20px]">mail</span>
</div>
</div>
</div>
<!-- Password Field -->
<div class="flex flex-col gap-2">
<div class="flex items-center justify-between">
<label class="text-sm font-medium leading-none text-[#181511] dark:text-gray-200" for="password">
Password
</label>
</div>
<div class="relative">
<input class="flex h-12 w-full rounded-lg border border-[#e6e2db] bg-white px-3 py-2 text-sm text-[#181511] placeholder:text-[#8a7b60]/60 focus:border-primary-admin focus:outline-none focus:ring-1 focus:ring-primary-admin dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500" id="password" name="password" placeholder="••••••••••••" required="" type="password"/>
<button class="absolute right-3 top-1/2 flex -translate-y-1/2 items-center justify-center text-slate-400 transition-colors hover:text-slate-600 dark:hover:text-slate-300" type="button">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</button>
</div>
</div>
<!-- Login Button -->
<button class="mt-2 flex h-12 w-full items-center justify-center rounded-lg bg-primary-admin px-4 text-sm font-bold text-white transition-colors hover:bg-primary-admin-dark focus:outline-none focus:ring-2 focus:ring-primary-admin focus:ring-offset-2 dark:text-[#181511]" type="submit">
Login
</button>
</form>
</div>
<!-- Footer Copyright -->
<div class="mt-8 text-center text-xs text-white/40">
© 2025 BrokerBase. All rights reserved.
</div>
</div>
</div>
</body>
</html>