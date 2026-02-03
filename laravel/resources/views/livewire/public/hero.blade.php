<div>
<section class="bg-gradient-to-b from-white to-[#f6f6f8] px-6 lg:px-10 py-10">
    <div class="max-w-[1280px] mx-auto grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-10 items-start">
        <div class="flex flex-col gap-8">
            <div class="flex items-start gap-6">
                <div class="h-28 w-28 lg:h-32 lg:w-32 shrink-0 rounded-full bg-cover bg-center border-4 border-white shadow-lg ring-1 ring-gray-100"
                     data-alt="{{ $settings['agency_name'] ?? 'Elite Homes' }} company logo avatar"
                     style="background-image: url('{{ $settings['logo_url'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCZGvkDKAF1w7WbeFeUNmOM3NRSjHgmhSeryZM7vDVZ1m4ipcSRXPbXSEd2id5wazq_oIOrOECQqI9YWyoWlbbH2hXEX33P14Q3zghNi1ql4tBZGpuTE5NvyUY4ZTQJBmwaOlHrNFtmKJZ5hlyLxVkDdbsRnUKh523LtkEq96u8kK6SNVuz5caz2ymq71nBnay5rA4-tCzvVqaPnmBNsnRYGgYVWooVyVl0TRj85yqteKd7hSy3zjvwglp6ZBELj2yif6o7tUd4K-Hz' }}"></div>
                <div class="flex flex-col gap-2 pt-2">
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="text-3xl font-bold text-[#121317]">{{ $settings['agency_name'] ?? 'Loading...' }}</h2>
                        @if($settings['rera_id'] ?? false)
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold uppercase tracking-wide border border-green-200">RERA Registered</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 text-[#666e85] text-base">
                        <span class="material-symbols-outlined text-[20px] text-primary">location_on</span>
                        <p>{{ $settings['office_address'] ?? 'Loading location...' }}</p>
                    </div>
                    @if($settings['rera_id'] ?? false)
                        <p class="text-[#666e85] text-sm font-medium">RERA ID: <span>{{ $settings['rera_id'] ?? 'Loading...' }}</span></p>
                    @endif
                </div>
            </div>
            <div class="flex gap-6 mt-4">
                <div class="bg-white rounded-xl p-4 flex flex-col items-center shadow-sm border border-[#eef0f3]">
                    <span class="text-2xl font-bold text-primary">{{ $this->activeListingsCount }}</span>
                    <span class="text-xs text-[#666e85] font-medium uppercase tracking-wider">Active Listings</span>
                </div>
                <div class="bg-white rounded-xl p-4 flex flex-col items-center shadow-sm border border-[#eef0f3]">
                    <span class="text-2xl font-bold text-primary">{{ $this->totalPropertiesCount }}</span>
                    <span class="text-xs text-[#666e85] font-medium uppercase tracking-wider">Total Properties</span>
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-3 h-full min-h-[220px] md:min-h-0">
            <div class="relative flex-1 rounded-xl overflow-hidden border border-gray-200 shadow-sm bg-gray-100 min-h-[160px]">
                <iframe allowfullscreen="" class="absolute inset-0 w-full h-full border-0 grayscale-[20%] opacity-90 hover:opacity-100 transition-opacity" loading="lazy" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.1839487053644!2d-73.98773128459413!3d40.75890017932676!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25855c6480299%3A0x55194ec5a1ae072e!2sTimes%20Square!5e0!3m2!1sen!2sus!4v1633023222533!5m2!1sen!2sus">
                </iframe>
                <div class="absolute bottom-2 right-2 bg-white/90 backdrop-blur px-2 py-1 rounded-md text-[10px] font-bold shadow-sm pointer-events-none text-gray-600">
                    Locate Us
                </div>
            </div>
            <div class="flex gap-3">
                <a href="tel:{{ $this->getCleanedPhoneNumber() }}" class="flex-1 h-12 bg-primary hover:bg-blue-800 text-white font-bold rounded-xl shadow-md shadow-blue-900/10 hover:shadow-lg transition-all flex items-center justify-center gap-2 group">
                    <span class="material-symbols-outlined text-[20px] group-hover:scale-110 transition-transform">call</span>
                    Call Dealer
                </a>
                <a href="{{ $this->getWhatsAppMessage() }}" target="_blank" rel="noopener noreferrer" class="flex-1 h-12 bg-whatsapp hover:brightness-105 text-white font-bold rounded-xl shadow-md shadow-green-900/10 hover:shadow-lg transition-all flex items-center justify-center gap-2 group">
                    <i class="fa-brands fa-whatsapp text-[20px] group-hover:scale-110 transition-transform"></i>
                    WhatsApp
                </a>
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
</div>