@extends('layouts.admin')

@section('title', 'BrokerBase - Settings')

@section('header-content')
<nav aria-label="Breadcrumb" class="hidden sm:flex">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 text-sm text-gray-500">
        <li class="inline-flex items-center">
            <a class="hover:text-royal-blue transition-colors" href="{{ url('/admin/dashboard') }}">Home</a>
        </li>
        <li>
            <div class="flex items-center">
                <span class="material-symbols-outlined text-[16px] text-gray-400">chevron_right</span>
                <a class="ml-1 hover:text-royal-blue transition-colors" href="{{ url('/admin/settings') }}">Settings</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <span class="material-symbols-outlined text-[16px] text-gray-400">chevron_right</span>
                <span class="ml-1 font-medium text-gray-700">Account</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div x-data="settingsData()" x-init="init()" class="flex flex-col gap-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-slate-900 text-3xl font-black leading-tight tracking-tight">Account Settings</h1>
            <p class="text-gray-500 mt-1 text-sm">Manage your profile and website branding.</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <button @click="saveSettings" :disabled="saving" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-amber-500 text-white px-6 py-2.5 rounded-lg text-sm font-bold hover:bg-amber-600 transition-all shadow-md hover:shadow-lg focus:ring-4 focus:ring-amber-200 disabled:opacity-50 disabled:cursor-not-allowed">
                <span class="material-symbols-outlined text-[20px]" x-show="!saving">save</span>
                <span class="material-symbols-outlined text-[20px] animate-spin" x-show="saving" x-cloak>refresh</span>
                <span x-text="saving ? 'Saving...' : 'Save Changes'"></span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <div class="lg:col-span-7 xl:col-span-8 flex flex-col gap-8">
            <div class="bg-white p-6 md:p-8 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                    <div class="p-2 bg-blue-50 text-royal-blue rounded-lg">
                        <span class="material-symbols-outlined">business</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Agency Details</h2>
                        <p class="text-xs text-gray-500">Your core business information displayed on listings.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Agency Name</label>
                        <input x-model="agencyName" class="w-full rounded-lg border-gray-200 text-slate-900 text-sm focus:border-amber-500 focus:ring-amber-500" placeholder="e.g. Elite Homes" type="text" value="Elite Homes Real Estate"/>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">RERA / License ID</label>
                        <input x-model="reraId" class="w-full rounded-lg border-gray-200 text-slate-900 text-sm focus:border-amber-500 focus:ring-amber-500" placeholder="License Number" type="text" value="ORN-882910"/>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">WhatsApp Number</label>
                        <div class="flex rounded-lg shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 text-gray-500 text-sm">
                                +91
                            </span>
                            <input x-model="whatsappNumber" class="flex-1 min-w-0 block w-full rounded-none rounded-r-lg border-gray-200 text-sm focus:border-amber-500 focus:ring-amber-500" placeholder="50 000 0000" type="text" value="50 123 4567"/>
                        </div>
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Office Address</label>
                        <textarea x-model="officeAddress" class="w-full rounded-lg border-gray-200 text-slate-900 text-sm focus:border-amber-500 focus:ring-amber-500" placeholder="Enter your full office address" rows="3">Office 402, Business Bay Tower, Dubai, UAE</textarea>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 md:p-8 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                    <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                        <span class="material-symbols-outlined">palette</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Branding & Appearance</h2>
                        <p class="text-xs text-gray-500">Customize how your white-label site looks to clients.</p>
                    </div>
                </div>
                <div class="space-y-8">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-3">Agency Logo</label>
                        <div class="flex items-start gap-6">
                            <div class="shrink-0">
                                <div class="size-20 rounded-full border-2 border-gray-100 shadow-sm p-1 bg-white">
                                    <img :src="logoUrl" alt="Current Logo" class="w-full h-full object-cover rounded-full"/>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-center rounded-lg border border-dashed border-gray-300 px-6 py-8 hover:bg-gray-50 transition-colors cursor-pointer group">
                                    <div class="text-center">
                                        <span class="material-symbols-outlined mx-auto text-gray-400 text-3xl mb-2 group-hover:text-amber-500 transition-colors">cloud_upload</span>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label class="relative cursor-pointer rounded-md bg-transparent font-semibold text-amber-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-amber-500 focus-within:ring-offset-2 hover:text-amber-500" for="file-upload">
                                                <span>Upload a file</span>
                                                <input @change="handleLogoUpload" class="sr-only" id="file-upload" name="file-upload" type="file" accept="image/*"/>
                                            </label>
                                            <p class="pl-1 hidden md:inline">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1 hidden md:block">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <label class="block text-sm font-medium text-slate-700">Color Changer</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                Coming Soon
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-400">More Feature under development</span>
                            <span class="material-symbols-outlined text-gray-400 text-[18px]">construction</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-5 xl:col-span-4 relative">
            <div class="sticky top-6">
                <div class="flex items-center justify-between mb-4 px-2">
                    <h3 class="font-bold text-slate-800">Live Preview (Beta)</h3>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 animate-pulse">
                        <span class="size-2 bg-green-500 rounded-full"></span>
                                         Real-time
                                     </span>
                </div>
                <div class="mx-auto border-[12px] border-gray-900 rounded-[2.5rem] overflow-hidden shadow-2xl bg-gray-900 max-w-[320px]">
                    <div class="bg-gray-50 h-[600px] w-full relative flex flex-col overflow-hidden">
                        <div class="h-6 bg-black text-white text-[10px] flex justify-between items-center px-4 select-none">
                            <span>9:41</span>
                            <div class="flex gap-1">
                                <span class="material-symbols-outlined text-[12px]">signal_cellular_alt</span>
                                <span class="material-symbols-outlined text-[12px]">wifi</span>
                                <span class="material-symbols-outlined text-[12px]">battery_full</span>
                            </div>
                        </div>
                        <div class="bg-royal-blue px-4 py-3 flex items-center justify-between shadow-md z-10" :style="`background-color: ${getPreviewColor()}`">
                            <div class="flex items-center gap-2">
                                <div class="size-8 rounded-full bg-white p-0.5 overflow-hidden">
                                    <img :src="logoUrl" alt="Logo" class="w-full h-full object-cover rounded-full"/>
                                </div>
                                <span class="text-white font-bold text-sm truncate max-w-[120px]" x-text="agencyName || 'Agency Name'"></span>
                            </div>
                            <span class="material-symbols-outlined text-white">menu</span>
                        </div>
                        <div class="flex-1 overflow-y-auto no-scrollbar">
                            <div class="relative h-48 bg-gray-200">
                                <img alt="Property" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCToc03ewI-R-MLH1VeaILvpzcsPrzcNl35tCllapTZwSgSR39FEB-O03otqjWOPaQcd-FItQ4ORhThF5Ph3HmSpDPRgp1FgiERkSyWa_HVyO0UAkX8ApEuSzr8Z15ELVzKGK2pqUeHYTW4Ar_ZjAVyN-hy7GRG9SX86kKSlbXaRaHpijSfGxAa_XmtxQxozG8aaQRu7OlewhaXfNoZLh9hcU0aPLn-Us23Btb3P7qcH_zGOl8RrHEakkzwn2n7KGBDwjm-oBB_f70f"/>
                                <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wide text-gray-800">
                                    Featured
                                </div>
                                <div class="absolute bottom-3 right-3 bg-gray-900/80 backdrop-blur-sm px-2 py-1 rounded text-white text-xs flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">photo_camera</span> 1/12
                                </div>
                            </div>
                            <div class="p-4 bg-white">
                                <div class="flex justify-between items-start mb-2">
                                    <h2 class="text-base font-bold text-gray-900 leading-tight">Luxury Villa in Palms</h2>
                                    <span class="material-symbols-outlined text-gray-400 text-lg">favorite</span>
                                </div>
                                <p class="text-xs text-gray-500 mb-3 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">location_on</span>
                                    Palm Jumeirah, Dubai
                                </p>
                                <div class="flex items-end gap-1 mb-4">
                                    <span class="text-lg font-bold text-gold">₹ 15,000,000</span>
                                </div>
                                <div class="mt-6">
                                    <!-- Mobile: 4-col centered grid -->
                                    <div class="grid grid-cols-4 gap-1 lg:flex lg:flex-wrap lg:gap-2">
                                        <div class="flex items-center justify-center p-2 rounded-xl bg-background-light dark:bg-gray-800/50 lg:flex-row lg:px-3 lg:py-2">
                                            <span class="material-symbols-outlined text-gray-700 dark:text-gray-300 text-[14px] lg:mr-2">bed</span>
                                            <span class="text-[10px] font-bold text-gray-900 dark:text-white lg:text-xs">5 Beds</span>
                                        </div>
                                        <div class="flex items-center justify-center p-2 rounded-xl bg-background-light dark:bg-gray-800/50 lg:flex-row lg:px-3 lg:py-2">
                                            <span class="material-symbols-outlined text-gray-700 dark:text-gray-300 text-[14px] lg:mr-2">bathtub</span>
                                            <span class="text-[10px] font-bold text-gray-900 dark:text-white lg:text-xs">6 Baths</span>
                                        </div>
                                        <div class="flex items-center justify-center p-2 rounded-xl bg-background-light dark:bg-gray-800/50 lg:flex-row lg:px-3 lg:py-2">
                                            <span class="material-symbols-outlined text-gray-700 dark:text-gray-300 text-[14px] lg:mr-2">square_foot</span>
                                            <span class="text-[10px] font-bold text-gray-900 dark:text-white lg:text-xs">7,200 Sqft</span>
                                        </div>
                                        <div class="flex items-center justify-center p-2 rounded-xl bg-background-light dark:bg-gray-800/50 lg:flex-row lg:px-3 lg:py-2">
                                            <span class="material-symbols-outlined text-gray-700 dark:text-gray-300 text-[14px] lg:mr-2">apartment</span>
                                            <span class="text-[10px] font-bold text-gray-900 dark:text-white lg:text-xs">3 Flr</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-xs text-gray-600 line-clamp-3 leading-relaxed">
                                        Experience luxury living at its finest in this stunning villa located in the heart of Palm Jumeirah. Featuring private beach access, infinity pool, and state-of-the-art amenities...
                                    </p>
                                    <span class="text-blue-900 text-xs font-semibold cursor-pointer">Read more</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-4 border-t border-gray-100 grid grid-cols-2 gap-3 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                            <button class="flex items-center justify-center gap-2 bg-blue-900 text-white rounded-lg py-2.5 text-xs font-bold shadow-sm active:scale-95 transition-transform" :style="`background-color: ${getPreviewColor()}`">
                                <span class="material-symbols-outlined text-[16px]">call</span>
                                Call Now
                            </button>
                            <button class="flex items-center justify-center gap-2 bg-green-600 text-white rounded-lg py-2.5 text-xs font-bold shadow-sm active:scale-95 transition-transform">
                                <i class="fa-brands fa-whatsapp text-[16px]"></i>
                                WhatsApp
                            </button>
                        </div>
                        <div class="h-4 bg-white flex justify-center items-end pb-1">
                            <div class="w-1/3 h-1 bg-gray-300 rounded-full"></div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4 text-xs text-gray-400">
                    Preview updates as you type
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<script>
function settingsData() {
    return {
        agencyName: 'Elite Homes Real Estate',
        reraId: 'ORN-882910',
        whatsappNumber: '50 123 4567',
        officeAddress: 'Office 402, Business Bay Tower, Dubai, UAE',
        logoUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDjlFF_nSTOQN2xN5XEhoei2r1xmo6006_o8UoGMAFUfEAomAjyJR-_bXnIPonwd3cqDG7sOU8o_DGuG6ynBK32KcH-lRZpx1OAvvrV7EALzre8oOHD4wHQDNcs1u-RqUpqp6rABg-PLwMMJpYI1mwd0rmsHsf0SI7DMC0X71sycCni1WxVUk61lnXtb-Wzonan3tvT7xcDV3vnvIuNyz4n4mt6oBDAaqb4Ch5zP_c1FPKCfCmqMwaC598j6zQlRK21aawjBmED-Tjo',
        logoFile: null,
        selectedColor: 'blue',
        customColor: '1E3A8A',
        saving: false,

        async init() {
            await this.loadSettings();
        },

        async loadSettings() {
            try {
                const response = await fetch('/api/settings');
                const data = await response.json();
                if (data.success) {
                    const settings = data.data;
                    this.agencyName = settings.agency_name || 'Elite Homes Real Estate';
                    this.reraId = settings.rera_id || 'ORN-882910';
                    this.whatsappNumber = settings.w_no ? settings.w_no.replace('+91 ', '') : '50 123 4567';
                    this.officeAddress = settings.office_address || 'Office 402, Business Bay Tower, Dubai, UAE';
                    this.logoUrl = settings.logo_url || 'https://lh3.googleusercontent.com/aida-public/AB6AXuDjlFF_nSTOQN2xN5XEhoei2r1xmo6006_o8UoGMAFUfEAomAjyJR-_bXnIPonwd3cqDG7sOU8o_DGuG6ynBK32KcH-lRZpx1OAvvrV7EALzre8oOHD4wHQDNcs1u-RqUpqp6rABg-PLwMMJpYI1mwd0rmsHsf0SI7DMC0X71sycCni1WxVUk61lnXtb-Wzonan3tvT7xcDV3vnvIuNyz4n4mt6oBDAaqb4Ch5zP_c1FPKCfCmqMwaC598j6zQlRK21aawjBmED-Tjo';
                    this.selectedColor = settings.theme_color || 'blue';
                    this.customColor = settings.custom_color || '1E3A8A';
                }
            } catch (error) {
                console.error('Error loading settings:', error);
            }
        },

        selectColor(color) {
            this.selectedColor = color;
            this.updatePreviewColor();
        },

        updateCustomColor() {
            this.selectedColor = 'custom';
            this.updatePreviewColor();
        },

        getPreviewColor() {
            const colors = {
                blue: '#1e3a8a',
                emerald: '#059669',
                red: '#dc2626',
                amber: '#f59e0b',
                black: '#000000',
                custom: '#' + this.customColor
            };
            return colors[this.selectedColor] || colors.blue;
        },

        updatePreviewColor() {
            // This will trigger reactivity in the preview
        },

        handleLogoUpload(event) {
            const file = event.target.files[0];
            if (file) {
                // Store file for upload
                this.logoFile = file;
                
                // Show preview immediately
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.logoUrl = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        async saveSettings() {
            this.saving = true;
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Use FormData for file upload
                const formData = new FormData();
                formData.append('agency_name', this.agencyName);
                formData.append('rera_id', this.reraId);
                formData.append('w_no', '+91 ' + this.whatsappNumber);
                formData.append('office_address', this.officeAddress);
                formData.append('theme_color', this.selectedColor);
                formData.append('custom_color', this.customColor);
                
                if (this.logoFile) {
                    formData.append('logo', this.logoFile);
                }

                const response = await fetch('/admin/settings/update', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                        // Don't set Content-Type for FormData - browser sets it with boundary
                    },
                    body: formData
                });

                const data = await response.json();
                
                if (data.success) {
                    // Update logo preview with actual uploaded URL
                    if (data.settings.logo_url) {
                        this.logoUrl = data.settings.logo_url;
                    }
                    this.logoFile = null;
                    alert('Settings saved successfully!');
                } else {
                    throw new Error(data.message || 'Unknown error occurred');
                }
            } catch (error) {
                console.error('Error saving settings:', error);
                alert('Error saving settings: ' + error.message + '. Please try again.');
            } finally {
                this.saving = false;
            }
        }
    }
}
</script>
@endsection