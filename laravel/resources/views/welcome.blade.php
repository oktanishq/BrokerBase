<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Elite Homes - Dealer Homepage</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
@vite('resources/css/app.css')
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
<body class="bg-background-light dark:bg-background-dark text-[#121317] font-display min-h-screen flex flex-col" x-data="{ showMoreFilters: false }">
<main class="w-full bg-white min-h-screen flex flex-col relative pb-12 mx-auto">
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-[#f1f1f4] px-4 sm:px-6 lg:px-10 py-3 sm:py-4 flex items-center justify-between transition-all duration-200">
<div class="flex items-center gap-4">
<div class="h-12 w-12 rounded-full bg-cover bg-center border border-gray-100" data-alt="Elite Homes company logo avatar" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCZGvkDKAF1w7WbeFeUNmOM3NRSjHgmhSeryZM7vDVZ1m4ipcSRXPbXSEd2id5wazq_oIOrOECQqI9YWyoWlbbH2hXEX33P14Q3zghNi1ql4tBZGpuTE5NvyUY4ZTQJBmwaOlHrNFtmKJZ5hlyLxVkDdbsRnUKh523LtkEq96u8kK6SNVuz5caz2ymq71nBnay5rA4-tCzvVqaPnmBNsnRYGgYVWooVyVl0TRj85yqteKd7hSy3zjvwglp6ZBELj2yif6o7tUd4K-Hz');"></div>
<h1 class="text-xl font-bold text-[#121317] tracking-tight">Elite Homes</h1>
</div>
<div class="flex gap-2 sm:gap-3">
<button class="flex items-center justify-center gap-2 h-10 px-3 sm:px-4 rounded-full bg-[#f1f1f4] text-[#121317] hover:bg-gray-200 transition-colors font-medium text-sm">
<span class="material-symbols-outlined text-[18px] sm:text-[20px]">ios_share</span>
<span class="hidden sm:inline">Share Profile</span>
</button>
<button class="flex items-center justify-center h-10 w-10 rounded-full bg-[#f1f1f4] text-[#121317] hover:bg-gray-200 transition-colors">
<span class="material-symbols-outlined text-[18px] sm:text-[20px]">menu</span>
</button>
</div>
</header>
<section class="bg-gradient-to-b from-white to-[#f6f6f8] px-4 sm:px-6 lg:px-10 py-6 sm:py-8 lg:py-10">
<div class="max-w-[1280px] mx-auto grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-6 sm:gap-8 lg:gap-10 items-start">
<div class="flex flex-col gap-8">
<div class="flex items-start gap-4 sm:gap-5 lg:gap-6">
<div class="h-20 w-20 sm:h-24 sm:w-24 lg:h-28 lg:w-28 lg:h-32 lg:w-32 shrink-0 rounded-full bg-cover bg-center border-4 border-white shadow-lg ring-1 ring-gray-100" data-alt="Large profile picture of Elite Homes dealer" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDfVJGVzANp1NAi6tmqqdpbcRS7QYyqe9ksTG2UPH8r9snYAI-9ySrfL8rZ7r3j_LgPJCsthYOhRhez_8mxmm0Zpbr98BNZuX8jfQzYP_X1mBD_PivHUqoq3nmTjpVB-5VyRkBU0PhcfpICwJx1jdS-jXi7ty72wmF8lGrhcFL3wxEn8Fyx2WghziGtKozdELe8_-cTc56jAdTsG18w5vCDbQkRqSES-kjcsgocnYErJmwBbhyB5m9Llhb-yp9ISHJV_OKp9h5N3BtI');"></div>
<div class="flex flex-col gap-1 sm:gap-2 pt-1 sm:pt-2">
<div class="flex flex-wrap items-center gap-2 sm:gap-3">
<h2 class="text-2xl sm:text-3xl font-bold text-[#121317]">Elite Homes</h2>
<span class="px-2 sm:px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold uppercase tracking-wide border border-green-200">RERA Registered</span>
</div>
<div class="flex items-center gap-1 sm:gap-2 text-[#666e85] text-sm sm:text-base">
<span class="material-symbols-outlined text-[18px] sm:text-[20px] text-primary">location_on</span>
<p class="text-sm sm:text-base">123 Market St, Downtown, Metropolis</p>
</div>
<p class="text-[#666e85] text-sm font-medium">RERA ID: 12345678</p>
<div class="flex gap-3 sm:gap-4 lg:gap-6 mt-3 sm:mt-4">
<div class="bg-white rounded-xl px-6 py-3 shadow-sm border border-gray-100 flex flex-col items-center min-w-[120px]">
<span class="text-xl sm:text-2xl font-bold text-primary">12</span>
<span class="text-xs text-[#666e85] font-medium uppercase tracking-wider">Active Listings</span>
</div>
<div class="bg-white rounded-xl px-6 py-3 shadow-sm border border-gray-100 flex flex-col items-center min-w-[120px]">
<span class="text-xl sm:text-2xl font-bold text-primary">50+</span>
<span class="text-xs text-[#666e85] font-medium uppercase tracking-wider">Properties Sold</span>
</div>
</div>
</div>
</div>
</div>
<div class="flex flex-col gap-3 sm:gap-4">
<div class="flex gap-3 sm:gap-4 h-[160px] sm:h-[180px]">
<div class="relative flex-1 rounded-xl overflow-hidden border border-gray-200 shadow-sm bg-gray-100">
<iframe allowfullscreen="" class="absolute inset-0 w-full h-full border-0 grayscale-[20%] opacity-90 hover:opacity-100 transition-opacity" loading="lazy" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.1839487053644!2d-73.98773128459413!3d40.75890017932676!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25855c6480299%3A0x55194ec5a1ae072e!2sTimes%20Square!5e0!3m2!1sen!2sus!4v1633023222533!5m2!1sen!2sus">
</iframe>
<div class="absolute bottom-2 right-2 bg-white/90 backdrop-blur px-3 py-1 rounded-md text-[10px] font-bold shadow-sm pointer-events-none text-gray-600">
Locate Us
</div>
</div>
</div>
<div class="flex gap-3">
<button class="flex-1 h-12 bg-primary hover:bg-blue-800 text-white font-bold rounded-xl shadow-md shadow-blue-900/10 hover:shadow-lg transition-all flex items-center justify-center gap-2 group">
<span class="material-symbols-outlined text-[20px] group-hover:scale-110 transition-transform">call</span>
Call Dealer
</button>
<button class="flex-1 h-12 bg-whatsapp hover:brightness-105 text-white font-bold rounded-xl shadow-md shadow-green-900/10 hover:shadow-lg transition-all flex items-center justify-center gap-2 group sm:hidden">
<button class="hidden sm:flex flex-1 h-12 bg-whatsapp hover:brightness-105 text-white font-bold rounded-xl shadow-md shadow-green-900/10 hover:shadow-lg transition-all flex items-center justify-center gap-2 group">
<img alt="Whatsapp logo icon" class="w-5 h-5 invert brightness-0 grayscale-0 group-hover:scale-110 transition-transform" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAgPEGUEKjZPSkEHYkFnZ9Gx1JHOB4A1agO_DBYEWBuFwMC36Rd8RmQNnZI_gHOSxcW5jm5j7er5TGrbgjT0sz7NoQN3hgJN7vM_63MQhWoNuxGvHhkJhVwUgUA60YXth8XRgsFWRJCOj--W6_Q7ArnfLQpB8r7x-pvzyq0-DuRKBPv130bg0xhlun76EKVNL9J8LIuP-EyPP6RH-5JiA_PIrkeawFrQ2OCm_azTjM6_kaNnj0ET0fIB7wr692Oty0lpjIh_qdYfCpc"/>
WhatsApp
</button>
</div>
</div>
</div>
</section>
<section class="sticky top-[60px] sm:top-[73px] z-40 bg-white shadow-sm border-b border-gray-100 px-4 sm:px-6 lg:px-10 py-3 sm:py-4">
<div class="max-w-[1280px] mx-auto flex flex-col gap-3 sm:gap-4">
<div class="flex flex-col lg:flex-row items-center gap-4">
<div class="w-full lg:w-1/3 relative">
<label class="relative flex w-full items-center">
<span class="absolute left-4 text-[#666e85] material-symbols-outlined">search</span>
<input class="w-full bg-[#f1f1f4] text-[#121317] placeholder:text-[#666e85] h-12 sm:h-11 rounded-full pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-primary/20 border-none text-base transition-all" placeholder="Search location or building..." type="text"/>
</label>
</div>
<div class="w-full lg:w-2/3 flex flex-wrap gap-2 items-center justify-start lg:justify-end">
<button class="h-9 px-4 sm:px-5 rounded-full bg-primary text-white text-sm font-semibold shadow-md shadow-blue-900/10 hover:bg-blue-800 transition-colors">
All
</button>
<button class="h-9 px-4 sm:px-5 rounded-full bg-[#f1f1f4] text-[#121317] text-sm font-medium hover:bg-gray-200 transition-colors">
For Sale
</button>
<button class="h-9 px-4 sm:px-5 rounded-full bg-[#f1f1f4] text-[#121317] text-sm font-medium hover:bg-gray-200 transition-colors">
For Rent
</button>
<div class="w-px h-6 bg-gray-300 mx-1 hidden lg:block"></div>
<button class="h-9 px-4 sm:px-5 rounded-full bg-[#f1f1f4] text-[#121317] text-sm font-medium hover:bg-gray-200 transition-colors">
2 BHK
</button>
<button class="h-9 px-4 sm:px-5 rounded-full bg-[#f1f1f4] text-[#121317] text-sm font-medium hover:bg-gray-200 transition-colors">
3 BHK
</button>
<button class="h-9 px-4 sm:px-5 rounded-full bg-[#f1f1f4] text-[#121317] text-sm font-medium hover:bg-gray-200 transition-colors">
Commercial
</button>
</div>
</div>
<!-- Desktop Advanced Filters -->
<div class="hidden sm:flex flex-wrap items-center gap-3 pt-2 border-t border-gray-50">
<span class="text-xs font-semibold text-gray-400 uppercase tracking-wider mr-1">Advanced Filters:</span>
<div class="relative inline-block">
<select class="appearance-none h-9 pl-4 pr-9 rounded-lg bg-white border border-gray-200 text-[#121317] text-sm font-medium hover:border-gray-300 hover:bg-gray-50 transition-colors focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none cursor-pointer min-w-[140px]">
<option value="">Floor Preference</option>
<option value="ground">Ground Floor</option>
<option value="1-5">1st - 5th Floor</option>
<option value="5-10">5th - 10th Floor</option>
<option value="10+">10th+ Floor</option>
</select>
<span class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none material-symbols-outlined text-[16px] text-gray-500">expand_more</span>
</div>
<div class="relative inline-block">
<select class="appearance-none h-9 pl-4 pr-9 rounded-lg bg-white border border-gray-200 text-[#121317] text-sm font-medium hover:border-gray-300 hover:bg-gray-50 transition-colors focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none cursor-pointer min-w-[120px]">
<option value="">Bathrooms</option>
<option value="1">1 Bath</option>
<option value="2">2 Baths</option>
<option value="3">3+ Baths</option>
</select>
<span class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none material-symbols-outlined text-[16px] text-gray-500">expand_more</span>
</div>
<div class="relative inline-block">
<select class="appearance-none h-9 pl-4 pr-9 rounded-lg bg-white border border-gray-200 text-[#121317] text-sm font-medium hover:border-gray-300 hover:bg-gray-50 transition-colors focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none cursor-pointer min-w-[120px]">
<option value="">BHK Type</option>
<option value="1">1 BHK</option>
<option value="2">2 BHK</option>
<option value="3">3 BHK</option>
<option value="4">4+ BHK</option>
</select>
<span class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none material-symbols-outlined text-[16px] text-gray-500">expand_more</span>
</div>
<div class="relative inline-block">
<select class="appearance-none h-9 pl-4 pr-9 rounded-lg bg-white border border-gray-200 text-[#121317] text-sm font-medium hover:border-gray-300 hover:bg-gray-50 transition-colors focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none cursor-pointer min-w-[160px]">
<option value="">Carpet Area (sqft)</option>
<option value="0-500">0 - 500</option>
<option value="500-1000">500 - 1000</option>
<option value="1000-1500">1000 - 1500</option>
<option value="1500+">1500+</option>
</select>
<span class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none material-symbols-outlined text-[16px] text-gray-500">expand_more</span>
</div>
<button class="ml-auto text-sm text-primary hover:text-blue-700 font-medium underline-offset-2 hover:underline">Reset Filters</button>
</div>

<!-- Mobile More Filters Button -->
<div class="sm:hidden">
<button @click="showMoreFilters = !showMoreFilters" class="w-full h-10 bg-[#f1f1f4] text-[#121317] rounded-full flex items-center justify-center gap-2 text-sm font-medium hover:bg-gray-200 transition-colors">
<span class="material-symbols-outlined text-[18px]">tune</span>
More Filters
<span class="material-symbols-outlined text-[16px] transition-transform" :class="showMoreFilters ? 'rotate-180' : ''">expand_more</span>
</button>

<!-- Mobile Advanced Filters Dropdown -->
<div x-show="showMoreFilters" x-transition="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-0 translate-y-0" class="mt-3 bg-white border border-gray-200 rounded-xl p-4 shadow-lg">
<div class="grid grid-cols-2 gap-3">
<div class="relative">
<select class="appearance-none w-full h-9 pl-4 pr-8 rounded-lg bg-[#f1f1f4] text-[#121317] text-sm font-medium hover:bg-gray-200 transition-colors border-none focus:ring-0 cursor-pointer">
<option value="">Floor</option>
<option value="ground">Ground Floor</option>
<option value="1-5">1st - 5th Floor</option>
<option value="5-10">5th - 10th Floor</option>
<option value="10+">10th+ Floor</option>
</select>
<span class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none material-symbols-outlined text-[16px] text-gray-500">expand_more</span>
</div>
<div class="relative">
<select class="appearance-none w-full h-9 pl-4 pr-8 rounded-lg bg-[#f1f1f4] text-[#121317] text-sm font-medium hover:bg-gray-200 transition-colors border-none focus:ring-0 cursor-pointer">
<option value="">Bathroom</option>
<option value="1">1 Bath</option>
<option value="2">2 Baths</option>
<option value="3">3+ Baths</option>
</select>
<span class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none material-symbols-outlined text-[16px] text-gray-500">expand_more</span>
</div>
<div class="relative">
<select class="appearance-none w-full h-9 pl-4 pr-8 rounded-lg bg-[#f1f1f4] text-[#121317] text-sm font-medium hover:bg-gray-200 transition-colors border-none focus:ring-0 cursor-pointer">
<option value="">BHK Type</option>
<option value="1">1 BHK</option>
<option value="2">2 BHK</option>
<option value="3">3 BHK</option>
<option value="4">4+ BHK</option>
</select>
<span class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none material-symbols-outlined text-[16px] text-gray-500">expand_more</span>
</div>
<div class="relative">
<select class="appearance-none w-full h-9 pl-4 pr-8 rounded-lg bg-[#f1f1f4] text-[#121317] text-sm font-medium hover:bg-gray-200 transition-colors border-none focus:ring-0 cursor-pointer">
<option value="">Carpet Area</option>
<option value="0-500">0 - 500 sqft</option>
<option value="500-1000">500 - 1000 sqft</option>
<option value="1000-1500">1000 - 1500 sqft</option>
<option value="1500+">1500+ sqft</option>
</select>
<span class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none material-symbols-outlined text-[16px] text-gray-500">expand_more</span>
</div>
</div>
<button class="w-full mt-3 h-10 bg-primary text-white rounded-full font-medium text-sm hover:bg-blue-800 transition-colors">
Apply Filters
</button>
</div>
</div>
</div>
</section>
<section class="px-4 sm:px-6 lg:px-10 py-6 sm:py-8 bg-gray-50/50 flex-1">
<div class="max-w-[1280px] mx-auto">
<h3 class="text-lg sm:text-xl font-bold text-[#121317] mb-4 sm:mb-6">Featured Properties</h3>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
<article class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
<div class="relative aspect-[4/3] overflow-hidden">
<div class="absolute top-3 left-3 z-10 bg-primary text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
New Arrival
</div>
<div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
<button class="bg-white/90 backdrop-blur-md p-2 rounded-full text-gray-700 hover:text-red-500 hover:bg-white transition-colors shadow-sm">
<span class="material-symbols-outlined text-[20px] block">favorite</span>
</button>
</div>
<img alt="Modern luxury villa with pool at sunset" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB6JGLNEeRDULjyz7WFJ4ObKCZsQi74fcb4HMbuJJ5VwEvic_qApCHMX8vJ3Knawdusc02xCJkbMMw3C-ejtmLRxazLDraIoearbjj25VNyASPqtCfC0knpA8JNNVSKy8N5tTAcfsmro8U7Rw5LHMHKXZYoI93JICpm3AQNoW2T-CsfJTCVWbaOHWDSd2KDyz10TInVv0nJFeTbQCWYKnXKBCd7GVHrXGiZeWcgHrUY88JJDScTJ-mIIz7znDdPuVAbtllQISMOXo9j"/>
<div class="absolute bottom-0 left-0 w-full h-1/3 bg-gradient-to-t from-black/50 to-transparent pointer-events-none"></div>
</div>
<div class="p-5 flex flex-col gap-3 flex-1">
<div class="flex justify-between items-start">
<div>
<h3 class="text-gold font-bold text-lg sm:text-xl tracking-tight">$ 1,200,000</h3>
<h4 class="text-[#121317] font-bold text-base sm:text-lg leading-tight mt-1 group-hover:text-primary transition-colors">Luxury Hillside Villa</h4>
</div>
</div>
<div class="flex items-center gap-1 text-[#666e85] text-sm">
<span class="material-symbols-outlined text-[18px]">location_on</span>
<p class="truncate">Beverly Hills, Sunset Blvd</p>
</div>
<div class="flex items-center gap-4 py-3 border-t border-b border-gray-50 my-2 mt-auto">
<div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
<span class="material-symbols-outlined text-gray-400 text-[20px]">bed</span>
<span>4 Beds</span>
</div>
<div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
<span class="material-symbols-outlined text-gray-400 text-[20px]">bathtub</span>
<span>5 Baths</span>
</div>
<div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
<span class="material-symbols-outlined text-gray-400 text-[20px]">square_foot</span>
<span>3,500 sqft</span>
</div>
</div>
<div class="flex gap-3 mt-1">
<button class="flex-1 h-10 rounded-full border border-primary text-primary font-bold text-sm hover:bg-primary/5 transition-colors">
View Details
</button>
<button class="flex-1 h-10 rounded-full bg-whatsapp text-white font-bold text-sm flex items-center justify-center gap-2 hover:brightness-105 transition-all">
<img alt="Whatsapp logo icon" class="w-4 h-4 invert brightness-0 grayscale-0" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAgPEGUEKjZPSkEHYkFnZ9Gx1JHOB4A1agO_DBYEWBuFwMC36Rd8RmQNnZI_gHOSxcW5jm5j7er5TGrbgjT0sz7NoQN3hgJN7vM_63MQhWoNuxGvHhkJhVwUgUA60YXth8XRgsFWRJCOj--W6_Q7ArnfLQpB8r7x-pvzyq0-DuRKBPv130bg0xhlun76EKVNL9J8LIuP-EyPP6RH-5JiA_PIrkeawFrQ2OCm_azTjM6_kaNnj0ET0fIB7wr692Oty0lpjIh_qdYfCpc"/>
WhatsApp
</button>
</div>
</div>
</article>
<article class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
<div class="relative aspect-[4/3] overflow-hidden">
<div class="absolute top-3 left-3 z-10 bg-orange-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
Popular
</div>
<div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
<button class="bg-white/90 backdrop-blur-md p-2 rounded-full text-gray-700 hover:text-red-500 hover:bg-white transition-colors shadow-sm">
<span class="material-symbols-outlined text-[20px] block">favorite</span>
</button>
</div>
<img alt="Interior of a modern city apartment living room" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBT0lApEKfu4W1EpyNu2a-E_KsBSNhZJkLiCjGCYvummR4FC0a1kGMhIRQ5LRKU-4Okg66E-spvIJAXTRoGn1O--Ll6ZfgPHm5SMkYd97HoNw6rJvq09qg9Gw3_EDVQowqjfbmY4wz4d982yZUlvp9T3aSr3Zbvj4OMUC4ACM7mOtXJiaXjfPFOXvnmRg-_qDWdyzbsGcGLlxRckKV-YUWUN8-ekStNCiOYvxjBmX7EFNUFVMooeRqgUBmXYedjKo7lFLvj4oi5GxvT"/>
</div>
<div class="p-5 flex flex-col gap-3 flex-1">
<div>
<h3 class="text-gold font-bold text-lg sm:text-xl tracking-tight">$ 2,500 <span class="text-sm font-normal text-gray-500">/mo</span></h3>
<h4 class="text-[#121317] font-bold text-base sm:text-lg leading-tight mt-1 group-hover:text-primary transition-colors">Skyline City Apartment</h4>
</div>
<div class="flex items-center gap-1 text-[#666e85] text-sm">
<span class="material-symbols-outlined text-[18px]">location_on</span>
<p class="truncate">Downtown, 5th Avenue</p>
</div>
<div class="flex items-center gap-4 py-3 border-t border-b border-gray-50 my-2 mt-auto">
<div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
<span class="material-symbols-outlined text-gray-400 text-[20px]">bed</span>
<span>2 Beds</span>
</div>
<div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
<span class="material-symbols-outlined text-gray-400 text-[20px]">bathtub</span>
<span>2 Baths</span>
</div>
<div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
<span class="material-symbols-outlined text-gray-400 text-[20px]">square_foot</span>
<span>1,100 sqft</span>
</div>
</div>
<div class="flex gap-3 mt-1">
<button class="flex-1 h-10 rounded-full border border-primary text-primary font-bold text-sm hover:bg-primary/5 transition-colors">
View Details
</button>
<button class="flex-1 h-10 rounded-full bg-whatsapp text-white font-bold text-sm flex items-center justify-center gap-2 hover:brightness-105 transition-all">
<img alt="Whatsapp logo icon" class="w-4 h-4 invert brightness-0 grayscale-0" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDfsRkg7mANErfpfOt2Lv0x9EyxbJJR8EeXaXdAFalJjXCSbI4E7MTbmNwOv2WpZhD0p8QQRbLFIt6f98x1xWEIbPVtl1B-3NeV6AIqCHQ1mZh6UQqMKy6x448e68CRI48wu4OvpPElDum8QSlBrSK6L2iVCUICswg0VLMc4PPVvDpZ_o7u2ZIs7-vkIv6hWWbaS5mnOY0QvV3gss355KXNhk3Mb-YZWjeG1Xnx6tI89DfGoZVkIQ3P5W29A7bADevSTPBOzpYqi5ZH"/>
WhatsApp
</button>
</div>
</div>
</article>
<article class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
<div class="relative aspect-[4/3] overflow-hidden">
<div class="absolute top-3 left-3 z-10 bg-teal-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
Verified
</div>
<div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
<button class="bg-white/90 backdrop-blur-md p-2 rounded-full text-gray-700 hover:text-red-500 hover:bg-white transition-colors shadow-sm">
<span class="material-symbols-outlined text-[20px] block">favorite</span>
</button>
</div>
<img alt="Bright modern corporate office space interior" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC1E3fs-gJ1AVhYfRUC8Q2XGAbx_loyUQ5nQjgyZJfjbCJHzOX2-G70B8KZBIY2yoMlh4bPUpTkCEgaB-Ck7NlIXO7UB0F4C-5Od4cTJfNoadxt48BxF4gL3ilfKt_4TB1EPulqr_lxiThvhvgFG_Ymy8U5jAUKcr4xK5EDqq0vIETyRoKpjDFdwdD-6mTRTUbOp68mVQLRD6rhCFQYwQal2IOCkO2Wxx-yFMzAwpyF2WIJTBtHx1UYCwcNef2Lsjhde8z3t0QHxQnK"/>
</div>
<div class="p-5 flex flex-col gap-3 flex-1">
<div>
<h3 class="text-gold font-bold text-lg sm:text-xl tracking-tight">$ 15,000 <span class="text-sm font-normal text-gray-500">/mo</span></h3>
<h4 class="text-[#121317] font-bold text-base sm:text-lg leading-tight mt-1 group-hover:text-primary transition-colors">Tech Park Office Space</h4>
</div>
<div class="flex items-center gap-1 text-[#666e85] text-sm">
<span class="material-symbols-outlined text-[18px]">location_on</span>
<p class="truncate">Silicon Valley, Innovation Drive</p>
</div>
<div class="flex items-center gap-4 py-3 border-t border-b border-gray-50 my-2 mt-auto">
<div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
<span class="material-symbols-outlined text-gray-400 text-[20px]">domain</span>
<span>Commercial</span>
</div>
<div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
<span class="material-symbols-outlined text-gray-400 text-[20px]">meeting_room</span>
<span>5 Cabins</span>
</div>
<div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
<span class="material-symbols-outlined text-gray-400 text-[20px]">square_foot</span>
<span>2,000 sqft</span>
</div>
</div>
<div class="flex gap-3 mt-1">
<button class="flex-1 h-10 rounded-full border border-primary text-primary font-bold text-sm hover:bg-primary/5 transition-colors">
View Details
</button>
<button class="flex-1 h-10 rounded-full bg-whatsapp text-white font-bold text-sm flex items-center justify-center gap-2 hover:brightness-105 transition-all">
<img alt="Whatsapp logo icon" class="w-4 h-4 invert brightness-0 grayscale-0" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDYqWWB9wOKmYJr--ShfsGDmtb2rzJmUpSe_JUlvPVmIW8-4oArP11gGH8iNh96Opz7O-D5d7IZWU5wx0SiCy9h_hoXTy949M-hHP9bIkegNN50H5Eow6ajyG5YlVfCWNK6b4ajh_XErxxgDqm2L6DXhxfj-v23gy3pbZ2KuFZPEbY8qkJoA0LgOpX9DJr_1uWyy4p9CEs3Q4kTWSVMeGtqESCILFZ4MV7o3ZsmM3O6BN5TNw0U9bZSGNMKLL1tEO7VaZ-xOjTnD80o"/>
WhatsApp
</button>
</div>
</div>
</article>
</div>
</div>
</section>
<div class="fixed bottom-4 sm:bottom-6 right-4 sm:right-6 z-50 lg:hidden">
<button class="group flex items-center gap-2 bg-primary text-white h-12 sm:h-14 pl-4 sm:pl-5 pr-4 sm:pr-6 rounded-full shadow-xl shadow-blue-900/30 hover:scale-105 hover:bg-blue-800 transition-all duration-300">
<span class="material-symbols-outlined text-[20px] sm:text-[24px]">call</span>
<span class="font-bold text-sm sm:text-base">Contact Dealer</span>
</button>
</div>
</main>
</body>
</html>
