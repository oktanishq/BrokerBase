<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Elite Homes - Dealer Homepage</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
@vite('resources/css/app.css')
<script src="{{ asset('js/alpine-components.js') }}"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
</head>
<body class="bg-background-light dark:bg-background-dark text-[#121317] font-display min-h-screen flex flex-col">
<main class="w-full bg-white min-h-screen flex flex-col relative pb-12 mx-auto">
<x-public.site-header />
<livewire:public.hero />
<livewire:public.filters />

<x-public.listing />
</main>
@stack('scripts')

</body>
</html>
