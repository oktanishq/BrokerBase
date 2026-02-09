@props(['settings' => []])

<footer class="bg-blue-950 text-gray-300 border-t border-blue-900 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            <div>
                <div class="flex items-center space-x-2 mb-6">
                    <div class="h-10 w-10 bg-white rounded-full flex items-center justify-center text-primary font-bold text-lg overflow-hidden">
                        @if(!empty($settings['logo_url']))
                            <img src="{{ $settings['logo_url'] }}" alt="{{ $settings['agency_name'] ?? 'Logo' }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($settings['agency_name'] ?? 'BB', 0, 2) }}
                        @endif
                    </div>
                    <span class="font-bold text-2xl text-white tracking-tight">{{ $settings['agency_name'] ?? 'BrokerBase' }}</span>
                </div>
                <p class="text-blue-100/70 leading-relaxed mb-6">
                    Redefining real estate management with premium digital solutions for professional brokers and property seekers.
                </p>
                <div class="flex space-x-4">
                    <a class="bg-white/5 hover:bg-[#1877F2] hover:text-white p-2 rounded-full transition-all" href="{{ $settings['facebook_url'] ?? '#' }}">
                        <i class="fa-brands fa-facebook-f h-5 w-5"></i>
                    </a>
                    <a class="bg-white/5 hover:bg-[#E4405F] hover:text-white p-2 rounded-full transition-all" href="{{ $settings['instagram_url'] ?? '#' }}">
                        <i class="fa-brands fa-instagram h-5 w-5"></i>
                    </a>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-white mb-6 uppercase tracking-widest text-sm">Quick Links</h4>
                <ul class="space-y-4 text-sm">
                    <li><a class="hover:text-amber-500 transition-colors flex items-center group" href="#"><span class="material-icons text-xs mr-2 opacity-0 group-hover:opacity-100 transition-all -ml-4 group-hover:ml-0">chevron_right</span>About Us</a></li>
                    <li><a class="hover:text-amber-500 transition-colors flex items-center group" href="#"><span class="material-icons text-xs mr-2 opacity-0 group-hover:opacity-100 transition-all -ml-4 group-hover:ml-0">chevron_right</span>Featured</a></li>
                    <li><a class="hover:text-amber-500 transition-colors flex items-center group" href="#"><span class="material-icons text-xs mr-2 opacity-0 group-hover:opacity-100 transition-all -ml-4 group-hover:ml-0">chevron_right</span>Properties</a></li>
                    <li><a class="hover:text-amber-500 transition-colors flex items-center group" href="#"><span class="material-icons text-xs mr-2 opacity-0 group-hover:opacity-100 transition-all -ml-4 group-hover:ml-0">lock</span> Real Estate Blog</a></li>
                    <li><a class="hover:text-amber-500 transition-colors flex items-center group" href="#"><span class="material-icons text-xs mr-2 opacity-0 group-hover:opacity-100 transition-all -ml-4 group-hover:ml-0">chevron_right</span> Contact us</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-white mb-6 uppercase tracking-widest text-sm">Legal</h4>
                <ul class="space-y-4 text-sm">
                    <li><a class="hover:text-amber-500 transition-colors" href="#">Privacy Policy</a></li>
                    <li><a class="hover:text-amber-500 transition-colors" href="#">Terms of Use</a></li>
                    <li><a class="hover:text-amber-500 transition-colors" href="#">Terms of Service</a></li>
                    <li><a class="hover:text-amber-500 transition-colors" href="#">Cookie Policy</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-white mb-6 uppercase tracking-widest text-sm">Contact Details</h4>
                <ul class="space-y-4 text-sm">
                    <li class="flex items-start">
                        <span class="material-icons text-amber-500 mr-3 text-lg">location_on</span>
                        <span class="text-blue-100/70">{{ $settings['office_address'] ?? 'Office 402, Business Bay Tower, Dubai, UAE' }}</span>
                    </li>
                    <li class="flex items-center">
                        <span class="material-icons text-amber-500 mr-3 text-lg">call</span>
                        <span class="text-blue-100/70">{{ $settings['w_no'] ?? '+971 4 000 0000' }}</span>
                    </li>
                    <li class="flex items-center">
                        <span class="material-icons text-amber-500 mr-3 text-lg">mail</span>
                        <span class="text-blue-100/70">{{ $settings['email'] ?? 'hello@brokerbase.com' }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="pt-8 border-t border-blue-900/50 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex flex-wrap items-center justify-center gap-4">
                @if(!empty($settings['rera_id']))
                <div class="flex items-center space-x-2 md:space-x-3 bg-white/5 border border-white/10 px-4 md:px-6 py-2 md:py-3 rounded-full">
                    <div class="flex flex-col">
                        <span class="text-[9px] md:text-[10px] uppercase tracking-tighter md:tracking-tighter text-blue-300/60 font-bold">Government of India</span>
                        <span class="text-xs md:text-sm font-black text-white tracking-widest md:tracking-widest leading-none">RERA REGISTERED</span>
                    </div>
                </div>
                @endif
                <div class="flex items-center space-x-2 md:space-x-3 bg-white/5 border border-white/10 px-4 md:px-6 py-2 md:py-3 rounded-full">
                    <div class="flex flex-col">
                        <span class="text-[9px] md:text-[10px] uppercase tracking-tighter md:tracking-tighter text-green-600 font-bold">Verified By</span>
                        <span class="text-xs md:text-sm font-black text-white tracking-widest leading-none">BROKER BASE</span>
                    </div>
                </div>
            </div>
            <p class="text-sm text-blue-200/40">
                © {{ date('Y') }} {{ $settings['agency_name'] ?? 'BrokerBase' }}. All rights reserved.
            </p>
        </div>
    </div>
</footer>
