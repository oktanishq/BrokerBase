<div x-data="contactSectionData()" class="w-full bg-blue-50 dark:bg-blue-950/40 border-y border-blue-100 dark:border-blue-900 py-12 px-6">
    <div class="max-w-7xl mx-auto text-center">
        <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-4">Looking for something specific?</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-lg mx-auto">Our specialists are ready to help you find your dream property tailored to your needs.</p>
        <div class="flex gap-4 justify-center">
            <a :href="'tel:' + cleanedPhoneNumber" 
               class="flex-1 sm:flex-none h-12 px-8 bg-primary hover:bg-blue-800 text-white font-bold rounded-xl shadow-md shadow-blue-900/10 hover:shadow-lg transition-all flex items-center justify-center gap-2 group">
                <span class="material-symbols-outlined text-[20px] group-hover:scale-110 transition-transform">call</span>
                Contact Us
            </a>
            <a :href="whatsAppUrl" 
               target="_blank" 
               rel="noopener noreferrer"
               class="flex-1 sm:flex-none h-12 px-8 bg-whatsapp hover:brightness-105 text-white font-bold rounded-xl shadow-md shadow-green-900/10 hover:shadow-lg transition-all flex items-center justify-center gap-2 group">
                <i class="fa-brands fa-whatsapp text-[20px] group-hover:scale-110 transition-transform"></i>
                WhatsApp
            </a>
        </div>
    </div>
</div>

<script>
function contactSectionData() {
    return {
        settings: {},
        
        get cleanedPhoneNumber() {
            if (!this.settings.w_no) return '';
            return this.settings.w_no.replace(/\D/g, '');
        },
        
        get whatsAppUrl() {
            const phone = this.cleanedPhoneNumber;
            if (!phone) return '#';
            const message = encodeURIComponent('Hi! I\'m interested in your properties. Please share more details.');
            return `https://wa.me/${phone}?text=${message}`;
        },
        
        async init() {
            await this.loadSettings();
        },
        
        async loadSettings() {
            try {
                const response = await fetch('/api/settings');
                const data = await response.json();
                if (data.success) {
                    this.settings = data.data;
                }
            } catch (error) {
                console.error('Failed to load settings:', error);
            }
        }
    }
}
</script>
