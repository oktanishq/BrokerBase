<div>
<section class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mx-6 lg:mx-10 my-6">
    <div class="flex flex-col md:flex-row">
        <div class="p-6 md:p-8 flex-1 flex flex-col justify-center">
            <div class="flex items-start gap-4 mb-6">
                <div class="h-20 w-20 shrink-0 rounded-full bg-cover bg-center border-4 border-gray-50 shadow-md"
                     data-alt="{{ $settings['agency_name'] ?? 'Elite Homes' }} company logo avatar"
                     style="background-image: url('{{ $settings['logo_url'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCZGvkDKAF1w7WbeFeUNmOM3NRSjHgmhSeryZM7vDVZ1m4ipcSRXPbXSEd2id5wazq_oIOrOECQqI9YWyoWlbbH2hXEX33P14Q3zghNi1ql4tBZGpuTE5NvyUY4ZTQJBmwaOlHrNFtmKJZ5hlyLxVkDdbsRnUKh523LtkEq96u8kK6SNVuz5caz2ymq71nBnay5rA4-tCzvVqaPnmBNsnRYGgYVWooVyVl0TRj85yqteKd7hSy3zjvwglp6ZBELj2yif6o7tUd4K-Hz' }}"></div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $settings['agency_name'] ?? 'Loading...' }}</h2>
                        @if($settings['rera_id'] ?? false)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">RERA Registered</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 mt-1">
                        <span class="material-symbols-outlined text-base">location_on</span>
                        <p>{{ $settings['office_address'] ?? 'Loading location...' }}</p>
                    </div>
                    @if($settings['rera_id'] ?? false)
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">RERA ID: <span>{{ $settings['rera_id'] ?? 'Loading...' }}</span></p>
                    @endif
                </div>
            </div>
            <div class="flex gap-4 sm:gap-8 pt-2">
                <div class="bg-blue-50 dark:bg-blue-900/30 px-6 py-3 rounded-xl border border-blue-100 dark:border-blue-800 text-center">
                    <div class="text-2xl font-bold text-primary">{{ $this->activeListingsCount }}</div>
                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Active Listings</div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-3 rounded-xl border border-gray-100 dark:border-gray-600 text-center">
                    <div class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $this->totalPropertiesCount }}</div>
                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Properties</div>
                </div>
            </div>
            <div class="flex flex-wrap gap-4 pt-6 w-full sm:w-auto">
                <a href="tel:{{ $this->getCleanedPhoneNumber() }}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-primary hover:bg-blue-800 text-white rounded-xl font-medium transition-shadow shadow-lg shadow-blue-500/30 whitespace-nowrap">
                    <span class="material-symbols-outlined">call</span>
                    Contact Us
                </a>
                <a href="{{ $this->getWhatsAppMessage() }}" target="_blank" rel="noopener noreferrer" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-[#25D366] hover:bg-green-600 text-white rounded-xl font-medium transition-shadow shadow-lg shadow-green-500/30 whitespace-nowrap">
                    <i class="fa-brands fa-whatsapp text-[20px]"></i>
                    WhatsApp
                </a>
            </div>
        </div>
        <div class="md:w-1/3 h-48 md:h-auto bg-gray-200 dark:bg-gray-700 relative">
            <iframe allowfullscreen="" class="absolute inset-0 w-full h-full border-0 opacity-80" loading="lazy" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.1839487053644!2d-73.98773128459413!3d40.75890017932676!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25855c6480299%3A0x55194ec5a1ae072e!2sTimes%20Square!5e0!3m2!1sen!2sus!4v1633023222533!5m2!1sen!2sus"></iframe>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 p-2 rounded-full shadow-lg">
                    <span class="material-symbols-outlined text-red-500 text-3xl">place</span>
                </div>
            </div>
            <div class="absolute bottom-2 right-2">
                <span class="bg-white dark:bg-gray-800 text-xs px-2 py-1 rounded shadow text-gray-700 dark:text-gray-300">Locate Us</span>
            </div>
        </div>
    </div>
</section>

<div class="fixed bottom-6 right-6 z-10 lg:hidden">
    <a href="tel:{{ $this->getCleanedPhoneNumber() }}" class="group flex items-center gap-2 bg-primary text-white h-14 pl-5 pr-6 rounded-full shadow-xl shadow-blue-900/30 hover:scale-105 hover:bg-blue-800 transition-all duration-300">
        <span class="material-symbols-outlined text-[24px]">call</span>
        <span class="font-bold text-base">Contact Dealer</span>
    </a>
</div>
