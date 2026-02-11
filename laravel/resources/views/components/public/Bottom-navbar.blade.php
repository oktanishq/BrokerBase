<div x-data @toggle-mobile-search.window class="md:hidden sticky bottom-0 z-50 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 pb-[env(safe-area-inset-bottom)]">
    <div class="grid grid-cols-4 h-14">
        <a class="flex flex-col items-center justify-center text-primary" href="{{ url('/') }}">
            <span class="material-symbols-outlined">home</span>
            <span class="text-[10px] font-medium mt-1">Home</span>
        </a>
        <button @click="$dispatch('toggle-mobile-search')" class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-white transition-colors">
            <span class="material-symbols-outlined">search</span>
            <span class="text-[10px] font-medium mt-1">Search</span>
        </button>
        <button class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-white transition-colors">
            <span class="material-symbols-outlined">favorite</span>
            <span class="text-[10px] font-medium mt-1">Favorites</span>
        </button>
        <button @click="$dispatch('toggle-mobile-sidebar')" class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-white transition-colors">
            <span class="material-symbols-outlined">menu</span>
            <span class="text-[10px] font-medium mt-1">Menu</span>
        </button>
    </div>
</div>
