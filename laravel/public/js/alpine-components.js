/**
 * Alpine.js Component Registry
 * Central registry for all public components
 * Ensures proper loading order and prevents conflicts
 */

// Wait for Alpine.js to be ready
document.addEventListener('alpine:init', () => {
    console.log('Alpine.js Component Registry Initializing...');
    
    // Advanced Filters Component
    Alpine.data('advancedFilters', (params = {}) => ({
        // Component data
        showFilters: false,
        selectedCategory: 'propertyType',
        filters: {
            propertyType: '',
            minPrice: '',
            maxPrice: '',
            configuration: [],
            carpetArea: '',
            floorPreference: '',
            bathrooms: '',
            furnishing: ''
        },
        
        // Filter categories configuration
        filterCategories: [
            { key: 'propertyType', name: 'Property Type', icon: 'home' },
            { key: 'priceRange', name: 'Price Range', icon: 'attach_money' },
            { key: 'configuration', name: 'Configuration', icon: 'meeting_room' },
            { key: 'carpetArea', name: 'Carpet Area', icon: 'square_foot' },
            { key: 'floorPreference', name: 'Floor Preference', icon: 'stairs' },
            { key: 'bathrooms', name: 'Bathrooms', icon: 'bathtub' },
            { key: 'furnishing', name: 'Furnishing', icon: 'star' }
        ],

        // Filter options
        propertyTypes: [
            { value: '', label: 'All Types' },
            { value: 'residential', label: 'Residential' },
            { value: 'commercial', label: 'Commercial' },
            { value: 'industrial', label: 'Industrial' },
            { value: 'land', label: 'Land/Plot' }
        ],

        priceOptions: [
            { value: '10', label: '₹10 Lakh' },
            { value: '25', label: '₹25 Lakh' },
            { value: '50', label: '₹50 Lakh' },
            { value: '75', label: '₹75 Lakh' },
            { value: '100', label: '₹1 Crore' },
            { value: '150', label: '₹1.5 Crore' },
            { value: '200', label: '₹2 Crore' },
            { value: '300', label: '₹3 Crore+' }
        ],

        configurations: [
            { value: '1bhk', label: '1 BHK' },
            { value: '2bhk', label: '2 BHK' },
            { value: '3bhk', label: '3 BHK' },
            { value: '4bhk', label: '4 BHK' },
            { value: '4plus', label: '4+ BHK' }
        ],

        carpetAreas: [
            { value: '', label: 'Any Size' },
            { value: '0-500', label: '0 - 500 sqft' },
            { value: '500-1000', label: '500 - 1000 sqft' },
            { value: '1000-1500', label: '1000 - 1500 sqft' },
            { value: '1500-2000', label: '1500 - 2000 sqft' },
            { value: '2000+', label: '2000+ sqft' }
        ],

        floorOptions: [
            { value: '', label: 'Any Floor' },
            { value: 'ground', label: 'Ground Floor' },
            { value: '1-3', label: '1st - 3rd Floor' },
            { value: '4-7', label: '4th - 7th Floor' },
            { value: '8-10', label: '8th - 10th Floor' },
            { value: '10plus', label: '10th+ Floor' }
        ],

        bathroomOptions: [
            { value: '', label: 'Any' },
            { value: '1', label: '1 Bathroom' },
            { value: '2', label: '2 Bathrooms' },
            { value: '3', label: '3 Bathrooms' },
            { value: '4plus', label: '4+ Bathrooms' }
        ],

        furnishingOptions: [
            { value: '', label: 'Any' },
            { value: 'furnished', label: 'Furnished' },
            { value: 'semi-furnished', label: 'Semi-Furnished' },
            { value: 'unfurnished', label: 'Unfurnished' }
        ],

        // External callbacks (injected from Blade)
        onApply: params.onApply || function(filters) { 
            console.log("Applying filters:", filters); 
        },
        onReset: params.onReset || function() { 
            console.log("Resetting filters"); 
        },

        // Initialize with current filters if provided
        init() {
            if (params.currentFilters) {
                Object.keys(params.currentFilters).forEach(key => {
                    if (params.currentFilters[key] !== null && params.currentFilters[key] !== '') {
                        this.filters[key] = params.currentFilters[key];
                    }
                });
            }
        },

        // Get active filter count
        get activeFilterCount() {
            let count = 0;
            Object.keys(this.filters).forEach(key => {
                if (Array.isArray(this.filters[key])) {
                    count += this.filters[key].length;
                } else if (this.filters[key] !== '' && this.filters[key] !== null) {
                    count++;
                }
            });
            return count;
        },

        // Get filter count for specific category
        getFilterCount(categoryKey) {
            switch(categoryKey) {
                case 'propertyType':
                    return this.filters.propertyType ? 1 : 0;
                case 'priceRange':
                    return (this.filters.minPrice || this.filters.maxPrice) ? 1 : 0;
                case 'configuration':
                    return this.filters.configuration.length;
                case 'carpetArea':
                    return this.filters.carpetArea ? 1 : 0;
                case 'floorPreference':
                    return this.filters.floorPreference ? 1 : 0;
                case 'bathrooms':
                    return this.filters.bathrooms ? 1 : 0;
                case 'furnishing':
                    return this.filters.furnishing ? 1 : 0;
                default:
                    return 0;
            }
        },

        // Open filters modal
        openFilters() {
            this.showFilters = true;
            this.$nextTick(() => {
                // Store button position for desktop animation
                const button = this.$refs.filterButton;
                const modal = this.$refs.modalContent;
                
                if (window.innerWidth >= 768 && button && modal) {
                    const buttonRect = button.getBoundingClientRect();
                    modal.style.transformOrigin = `${buttonRect.left + buttonRect.width/2}px ${buttonRect.top + buttonRect.height/2}px`;
                }
            });
        },

        // Close filters modal
        closeFilters() {
            this.showFilters = false;
        },

        // Select filter category
        selectCategory(categoryKey) {
            this.selectedCategory = categoryKey;
        },

        // Reset current category filters
        resetCurrentCategory() {
            switch(this.selectedCategory) {
                case 'propertyType':
                    this.filters.propertyType = '';
                    break;
                case 'priceRange':
                    this.filters.minPrice = '';
                    this.filters.maxPrice = '';
                    break;
                case 'configuration':
                    this.filters.configuration = [];
                    break;
                case 'carpetArea':
                    this.filters.carpetArea = '';
                    break;
                case 'floorPreference':
                    this.filters.floorPreference = '';
                    break;
                case 'bathrooms':
                    this.filters.bathrooms = '';
                    break;
                case 'furnishing':
                    this.filters.furnishing = '';
                    break;
            }
        },

        // Reset all filters
        resetAllFilters() {
            Object.keys(this.filters).forEach(key => {
                if (Array.isArray(this.filters[key])) {
                    this.filters[key] = [];
                } else {
                    this.filters[key] = '';
                }
            });
            
            // Call external reset function
            this.onReset();
        },

        // Apply filters
        applyFilters() {
            // Filter out empty values
            const cleanFilters = {};
            Object.keys(this.filters).forEach(key => {
                if (Array.isArray(this.filters[key]) && this.filters[key].length > 0) {
                    cleanFilters[key] = this.filters[key];
                } else if (!Array.isArray(this.filters[key]) && this.filters[key] !== '' && this.filters[key] !== null) {
                    cleanFilters[key] = this.filters[key];
                }
            });

            // Call external apply function
            this.onApply(cleanFilters);

            this.closeFilters();
        }
    }));
    
    console.log('Alpine.js Component Registry Initialized Successfully');
});

// Global helper functions for component registration
window.AlpineComponents = {
    register: function(name, componentFn) {
        document.addEventListener('alpine:init', () => {
            Alpine.data(name, componentFn);
        });
    },
    
    init: function() {
        console.log('Component system ready');
    }
};