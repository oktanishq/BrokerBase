<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>BrokerBase - Dealer Homepage</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
@vite('resources/css/app.css')
<script src="{{ asset('js/alpine-components.js') }}"></script>

<style>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}
::-webkit-scrollbar-track {
    background: transparent;
}
::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
.dark ::-webkit-scrollbar-thumb {
    background: #4b5563;
}
.dark ::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
}
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>
</head>
<body class="bg-background-light dark:bg-background-dark text-gray-800 dark:text-gray-200 transition-colors duration-200">
<?php $settings = \App\Models\Setting::first()?->toArray() ?? []; ?>
<div class="flex min-h-screen">
    <livewire:public.sidebar />

    <main class="xl:ml-64 flex-1 flex flex-col min-w-0">
        <x-public.site-header />
        <livewire:public.hero />
        <x-public.Collection />
        <livewire:public.filters />
        <livewire:public.listings />
        <x-public.contact-section />
        <x-public.footer :settings="$settings" />
        <x-public.bottom-navbar />
    </main>
</div>
@stack('scripts')
</body>
</html>
