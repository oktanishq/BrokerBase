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
<h2 class="text-slate-900 text-lg font-bold leading-tight">Account Settings</h2>
<p class="text-sm text-gray-500 hidden sm:block">Manage your profile and website branding.</p>
@endsection

@section('content')
<div x-data="settingsData()" class="flex flex-col gap-8">
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
            <!-- Agency Details Form -->
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
                                +971
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

            <!-- Branding Section -->
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
                    <!-- Logo Upload -->
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
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Color Picker -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-3">Primary Theme Color</label>
                        <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                            <div class="flex items-center gap-3">
                                <button @click="selectColor('blue')" :class="selectedColor === 'blue' ? 'ring-2 ring-offset-2 ring-blue-900' : ''" class="size-8 rounded-full bg-blue-900 focus:outline-none hover:scale-110 transition-transform"></button>
                                <button @click="selectColor('emerald')" :class="selectedColor === 'emerald' ? 'ring-2 ring-offset-2 ring-emerald-600' : ''" class="size-8 rounded-full bg-emerald-600 focus:outline-none hover:scale-110 transition-all"></button>
                                <button @click="selectColor('red')" :class="selectedColor === 'red' ? 'ring-2 ring-offset-2 ring-red-600' : ''" class="size-8 rounded-full bg-red-600 focus:outline-none hover:scale-110 transition-all"></button>
                                <button @click="selectColor('amber')" :class="selectedColor === 'amber' ? 'ring-2 ring-offset-2 ring-amber-500' : ''" class="size-8 rounded-full bg-amber-500 focus:outline-none hover:scale-110 transition-all"></button>
                                <button @click="selectColor('black')" :class="selectedColor === 'black' ? 'ring-2 ring-offset-2 ring-gray-900' : ''" class="size-8 rounded-full bg-black focus:outline-none hover:scale-110 transition-all"></button>
                            </div>
                            <div class="h-8 w-px bg-gray-200 hidden md:block"></div>
                            <div class="flex items-center gap-2 w-full md:w-auto">
                                <span class="text-sm text-gray-500 whitespace-nowrap">Custom Hex:</span>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-gray-500 sm:text-sm">#</span>
                                    </div>
                                    <input x-model="customColor" @input="updateCustomColor" class="block w-28 rounded-lg border-gray-200 pl-7 pr-2 text-sm focus:border-amber-500 focus:ring-amber-500 font-mono" id="color" name="color" placeholder="1E3A8A" type="text" value="1E3A8A"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Mobile Preview -->
        <div class="lg:col-span-5 xl:col-span-4 relative">
            <div class="sticky top-6">
                <div class="flex items-center justify-between mb-4 px-2">
                    <h3 class="font-bold text-slate-800">Live Mobile Preview</h3>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 animate-pulse">
                        <span class="size-2 bg-green-500 rounded-full"></span>
                        Real-time
                    </span>
                </div>
                <div class="mx-auto border-[12px] border-gray-900 rounded-[2.5rem] overflow-hidden shadow-2xl bg-gray-900 max-w-[320px]">
                    <div class="bg-gray-50 h-[600px] w-full relative flex flex-col overflow-hidden">
                        <!-- Phone Header -->
                        <div class="h-6 bg-black text-white text-[10px] flex justify-between items-center px-4 select-none">
                            <span>9:41</span>
                            <div class="flex gap-1">
                                <span class="material-symbols-outlined text-[12px]">signal_cellular_alt</span>
                                <span class="material-symbols-outlined text-[12px]">wifi</span>
                                <span class="material-symbols-outlined text-[12px]">battery_full</span>
                            </div>
                        </div>
                        <!-- App Header -->
                        <div class="bg-royal-blue px-4 py-3 flex items-center justify-between shadow-md z-10" :style="`background-color: ${getPreviewColor()}`">
                            <div class="flex items-center gap-2">
                                <div class="size-8 rounded-full bg-white p-0.5 overflow-hidden">
                                    <img :src="logoUrl" alt="Logo" class="w-full h-full object-cover rounded-full"/>
                                </div>
                                <span class="text-white font-bold text-sm truncate max-w-[120px]" x-text="agencyName || 'Agency Name'"></span>
                            </div>
                            <span class="material-symbols-outlined text-white">menu</span>
                        </div>
                        <!-- Content -->
                        <div class="flex-1 overflow-y-auto no-scrollbar">
                            <!-- Property Image -->
                            <div class="relative h-48 bg-gray-200">
                                <img alt="Property" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCToc03ewI-R-MLH1VeaILvpzcsPrzcNl35tCllapTZwSgSR39FEB-O03otqjWOPaQcd-FItQ4ORhThF5Ph3HmSpDPRgp1FgiERkSyWa_HVyO0UAkX8ApEuSzr8Z15ELVzKGK2pqUeHYTW4Ar_ZjAVyN-hy7GRG9SX86kKSlbXaRaHpijSfGxAa_XmtxQxozG8aaQRu7OlewhaXfNoZLh9hcU0aPLn-Us23Btb3P7qcH_zGOl8RrHEakkzwn2n7KGBDwjm-oBB_f70f"/>
                                <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wide text-gray-800">
                                    Featured
                                </div>
                                <div class="absolute bottom-3 right-3 bg-gray-900/80 backdrop-blur-sm px-2 py-1 rounded text-white text-xs flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">photo_camera</span> 1/12
                                </div>
                            </div>
                            <!-- Property Details -->
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
                                    <span class="text-lg font-bold text-blue-900">AED 15,000,000</span>
                                </div>
                                <div class="flex gap-4 border-t border-b border-gray-100 py-3 mb-4">
                                    <div class="text-center flex-1 border-r border-gray-100">
                                        <span class="block text-sm font-bold text-gray-800">5</span>
                                        <span class="text-[10px] text-gray-400 uppercase">Beds</span>
                                    </div>
                                    <div class="text-center flex-1 border-r border-gray-100">
                                        <span class="block text-sm font-bold text-gray-800">6</span>
                                        <span class="text-[10px] text-gray-400 uppercase">Baths</span>
                                    </div>
                                    <div class="text-center flex-1">
                                        <span class="block text-sm font-bold text-gray-800">7,200</span>
                                        <span class="text-[10px] text-gray-400 uppercase">SqFt</span>
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
                        <!-- Action Buttons -->
                        <div class="bg-white p-4 border-t border-gray-100 grid grid-cols-2 gap-3 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                            <button class="flex items-center justify-center gap-2 bg-blue-900 text-white rounded-lg py-2.5 text-xs font-bold shadow-sm active:scale-95 transition-transform" :style="`background-color: ${getPreviewColor()}`">
                                <span class="material-symbols-outlined text-[16px]">call</span>
                                Call Now
                            </button>
                            <button class="flex items-center justify-center gap-2 bg-green-600 text-white rounded-lg py-2.5 text-xs font-bold shadow-sm active:scale-95 transition-transform">
                                <span class="material-symbols-outlined text-[16px]">chat</span>
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
<script>
function settingsData() {
    return {
        agencyName: 'Elite Homes Real Estate',
        reraId: 'ORN-882910',
        whatsappNumber: '50 123 4567',
        officeAddress: 'Office 402, Business Bay Tower, Dubai, UAE',
        logoUrl: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDjlFF_nSTOQN2xN5XEhoei2r1xmo6006_o8UoGMAFUfEAomAjyJR-_bXnIPonwd3cqDG7sOU8o_DGuG6ynBK32KcH-lRZpx1OAvvrV7EALzre8oOHD4wHQDNcs1u-RqUpqp6rABg-PLwMMJpYI1mwd0rmsHsf0SI7DMC0X71sycCni1WxVUk61lnXtb-Wzonan3tvT7xcDV3vnvIuNyz4n4mt6oBDAaqb4Ch5zP_c1FPKCfCmqMwaC598j6zQlRK21aawjBmED-Tjo',
        selectedColor: 'blue',
        customColor: '1E3A8A',
        saving: false,

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
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 2000));
                // Here you would make an actual API call to save the settings
                console.log('Settings saved:', {
                    agencyName: this.agencyName,
                    reraId: this.renaId,
                    whatsappNumber: this.whatsappNumber,
                    officeAddress: this.officeAddress,
                    logoUrl: this.logoUrl,
                    selectedColor: this.selectedColor,
                    customColor: this.customColor
                });
                // Show success message
                alert('Settings saved successfully!');
            } catch (error) {
                console.error('Error saving settings:', error);
                alert('Error saving settings. Please try again.');
            } finally {
                this.saving = false;
            }
        }
    }
}
</script>
@endsection