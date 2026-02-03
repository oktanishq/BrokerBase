<?php

namespace App\Livewire\Public;

use App\Models\Property;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;

class Listings extends Component
{
    use WithPagination;

    public $error = null;
    public $settings = [];
    public $currentFilters = [];
    public $searchQuery = '';
    public $categoryFilter = 'all';
    public $perPage = 6;  // Items per page
    public $jumpToPage = null;  // Jump to page input
    
    // Sorting - protected so it doesn't get serialized
    protected $sortBy = 'newest'; // 'newest', 'price_low', 'price_high', 'popular'
    
    // How many pages to show on each side of current page
    protected $onEachSide = 1;

    protected $listeners = [
        'filtersApplied' => 'applyFilters',
        'filtersReset' => 'resetFilters',
        'searchUpdated' => 'applySearch',
        'categoryUpdated' => 'applyCategory'
    ];

    public function mount()
    {
        $this->loadSettings();
        // Get sortBy from URL if present
        $this->sortBy = request()->get('sort', 'newest');
    }

    /**
     * Boot the component and check if current page is valid
     */
    public function boot()
    {
        // Check if we're on a page that no longer exists (e.g., after items were deleted or perPage changed)
        $this->checkPageValidity();
    }

    /**
     * Check if current page is valid and redirect to page 1 if not
     */
    protected function checkPageValidity()
    {
        // Get the current page from the query string
        $currentPage = $this->getPage();
        
        // Calculate total pages based on current filters
        $totalItems = $this->getFilteredTotalCount();
        $totalPages = $totalItems > 0 ? ceil($totalItems / $this->perPage) : 1;
        
        // If current page is greater than total pages, reset to page 1
        if ($currentPage > $totalPages && $currentPage > 1) {
            $this->setPage(1);
        }
    }

    public function loadSettings()
    {
        try {
            $this->settings = Setting::first()?->toArray() ?? [];
        } catch (\Exception $e) {
            $this->error = 'Failed to load settings';
        }
    }

    /**
     * Get properties for the view (lazy-loaded property)
     */
    public function getPropertiesProperty()
    {
        $query = Property::query();

        // Base filter: Only show available properties on homepage
        $query->where('status', 'available');

        // Apply additional filters
        $this->applyFiltersToQuery($query);
        
        // Apply sorting
        $this->applySorting($query);

        // Use pagination with perPage setting
        return $query->paginate($this->perPage);
    }

    /**
     * Sanitize search input to prevent SQL injection
     * Removes or escapes special SQL characters
     */
    protected function sanitizeSearchInput($input)
    {
        if (empty($input)) {
            return '';
        }
        
        // Convert to string and trim
        $input = trim((string) $input);
        
        // Remove or escape dangerous SQL characters
        // Allow alphanumeric, spaces, basic punctuation, and common property-related characters
        $input = preg_replace('/[\x00-\x1f\x7f]/u', '', $input); // Remove control characters
        $input = str_replace(["'", '"', '\\', '\x00', '\n', '\r', '\t'], '', $input);
        
        // Limit to 100 characters
        return substr($input, 0, 100);
    }

    /**
     * Validate that a value is numeric for price comparisons
     */
    protected function validateNumeric($value, $default = null)
    {
        if (empty($value) && $value !== '0') {
            return $default;
        }
        
        // Remove currency symbols, commas, and spaces
        $cleaned = preg_replace('/[^0-9.]/', '', (string) $value);
        
        // Validate it's a valid positive number
        if (!is_numeric($cleaned) || floatval($cleaned) < 0) {
            return $default;
        }
        
        return floatval($cleaned);
    }

    /**
     * Validate an array contains only numeric values
     */
    protected function validateNumericArray($array, $default = [])
    {
        if (empty($array) || !is_array($array)) {
            return $default;
        }
        
        return array_filter($array, function($value) {
            return is_numeric($value) && $value >= 0;
        });
    }

    /**
     * Get filtered total count (same logic as getPropertiesProperty but just counting)
     */
    protected function getFilteredTotalCount()
    {
        $query = Property::query();
        
        // Base filter: Only show available properties on homepage
        $query->where('status', 'available');
        
        // Apply search with sanitization
        $sanitizedSearch = $this->sanitizeSearchInput($this->searchQuery);
        if (!empty($sanitizedSearch)) {
            $searchTerm = '%' . strtolower($sanitizedSearch) . '%';
            $query->where(function($q) use ($sanitizedSearch, $searchTerm) {
                // Search by ID (exact match or partial) - validate numeric
                $numericId = $this->validateNumeric($sanitizedSearch);
                if ($numericId !== null) {
                    $q->where('id', 'like', '%' . $numericId . '%');
                }
                // Search by title (case-insensitive)
                $q->orWhereRaw('LOWER(title) LIKE ?', [$searchTerm])
                  // Search by address (case-insensitive)
                  ->orWhereRaw('LOWER(address) LIKE ?', [$searchTerm])
                  // Search by price (numeric comparison if input is numeric)
                  ->orWhere('price', 'like', '%' . $sanitizedSearch . '%');
            });
        }
        
        if ($this->categoryFilter !== 'all') {
            switch ($this->categoryFilter) {
                case 'sale':
                    $query->where('status', 'available');
                    break;
                case 'rent':
                    break;
                case '2bhk':
                    $query->where('bedrooms', 2);
                    break;
                case '3bhk':
                    $query->where('bedrooms', 3);
                    break;
                case 'commercial':
                    $query->where('property_type', 'commercial');
                    break;
            }
        }
        
        if (!empty($this->currentFilters['propertyType'])) {
            $query->where('property_type', $this->currentFilters['propertyType']);
        }
        
        if (!empty($this->currentFilters['minPrice'])) {
            $query->where('price', '>=', $this->currentFilters['minPrice']);
        }
        
        if (!empty($this->currentFilters['maxPrice'])) {
            $query->where('price', '<=', $this->currentFilters['maxPrice']);
        }
        
        if (!empty($this->currentFilters['configuration'])) {
            $query->whereIn('bedrooms', $this->currentFilters['configuration']);
        }
        
        if (!empty($this->currentFilters['carpetArea'])) {
            switch ($this->currentFilters['carpetArea']) {
                case '500':
                    $query->where('area_sqft', '<=', 500);
                    break;
                case '1000':
                    $query->whereBetween('area_sqft', [500, 1000]);
                    break;
                case '1500':
                    $query->whereBetween('area_sqft', [1000, 1500]);
                    break;
                case '2000':
                    $query->where('area_sqft', '>=', 1500);
                    break;
            }
        }
        
        if (!empty($this->currentFilters['bathrooms'])) {
            $validatedBathrooms = $this->validateNumeric($this->currentFilters['bathrooms']);
            if ($validatedBathrooms !== null) {
                $query->where('bathrooms', $validatedBathrooms);
            }
        }
        
        return $query->count();
    }

    /**
     * Handle jumpToPage property changes (validation only, no auto-navigation)
     */
    public function updatedJumpToPage($value)
    {
        // This method is kept for Livewire property hydration
        // Navigation is handled via jumpToPageAction() method
        // No auto-navigation on keystroke
    }

    /**
     * Jump to a specific page when Go button is clicked or Enter is pressed
     */
    public function jumpToPageAction()
    {
        if (!$this->jumpToPage) {
            return;
        }
        
        // Calculate total pages from filtered count (without accessing properties)
        $totalItems = $this->getFilteredTotalCount();
        $totalPages = $totalItems > 0 ? ceil($totalItems / $this->perPage) : 1;
        
        if ($this->jumpToPage >= 1 && $this->jumpToPage <= $totalPages) {
            $this->setPage($this->jumpToPage);
        }
        
        // Clear the input after navigation
        $this->jumpToPage = null;
    }

    /**
     * Get the pagination window for sliding window pagination
     */
    public function getPaginationWindowProperty()
    {
        $currentPage = $this->properties->currentPage();
        $totalPages = $this->totalPages;
        $onEachSide = $this->onEachSide;

        // If total pages is small, show all
        if ($totalPages <= 7) {
            return [
                'showAll' => true,
                'pages' => range(1, $totalPages),
            ];
        }

        // Calculate the window around current page
        $windowStart = max(1, $currentPage - $onEachSide);
        $windowEnd = min($totalPages, $currentPage + $onEachSide);

        // Determine if we need ellipsis
        $showLeftEllipsis = $windowStart > 2;
        $showRightEllipsis = $windowEnd < $totalPages - 1;

        // Build pages array
        $pages = [];

        // Always add first page
        $pages[] = 1;

        // Add left ellipsis if needed
        if ($showLeftEllipsis) {
            $pages[] = '...';
        }

        // Add pages in the window
        for ($i = $windowStart; $i <= $windowEnd; $i++) {
            if ($i > 1 && $i < $totalPages) {
                $pages[] = $i;
            }
        }

        // Add right ellipsis if needed
        if ($showRightEllipsis) {
            $pages[] = '...';
        }

        // Always add last page
        if ($totalPages > 1) {
            $pages[] = $totalPages;
        }

        return [
            'showAll' => false,
            'pages' => $pages,
        ];
    }

    public function getShowingFromProperty()
    {
        if ($this->properties->total() === 0) return 0;
        return ($this->properties->currentPage() - 1) * $this->properties->perPage() + 1;
    }

    public function getShowingToProperty()
    {
        return min($this->properties->currentPage() * $this->properties->perPage(), $this->properties->total());
    }

    public function getShowingCountProperty()
    {
        return $this->properties->total();
    }

    public function getTotalPagesProperty()
    {
        return $this->properties->lastPage();
    }

    /**
     * Apply sorting to query
     */
    protected function applySorting($query)
    {
        switch ($this->sortBy) {
            case 'newest':
                $query->latest('created_at');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
        }
    }

    /**
     * Update sort and refresh
     */
    public function setSort($sort)
    {
        $this->sortBy = $sort;
        return redirect()->route('home', ['sort' => $sort]);
    }

    public function applyFiltersToQuery($query)
    {
        // Apply search - case-insensitive, searches ID, title, address, and price
        $sanitizedSearch = $this->sanitizeSearchInput($this->searchQuery);
        if (!empty($sanitizedSearch)) {
            $searchTerm = '%' . strtolower($sanitizedSearch) . '%';
            $query->where(function($q) use ($sanitizedSearch, $searchTerm) {
                // Search by ID (exact match or partial) - validate numeric
                $numericId = $this->validateNumeric($sanitizedSearch);
                if ($numericId !== null) {
                    $q->where('id', 'like', '%' . $numericId . '%');
                }
                // Search by title (case-insensitive)
                $q->orWhereRaw('LOWER(title) LIKE ?', [$searchTerm])
                  // Search by address (case-insensitive)
                  ->orWhereRaw('LOWER(address) LIKE ?', [$searchTerm])
                  // Search by price (numeric comparison if input is numeric)
                  ->orWhere('price', 'like', '%' . $sanitizedSearch . '%');
            });
        }

        // Apply category filter
        if ($this->categoryFilter !== 'all') {
            switch ($this->categoryFilter) {
                case 'sale':
                    $query->where('status', 'available');
                    break;
                case 'rent':
                    break;
                case '2bhk':
                    $query->where('bedrooms', 2);
                    break;
                case '3bhk':
                    $query->where('bedrooms', 3);
                    break;
                case 'commercial':
                    $query->where('property_type', 'commercial');
                    break;
            }
        }

        // Apply advanced filters with validation
        if (!empty($this->currentFilters['propertyType'])) {
            $allowedTypes = ['apartment', 'villa', 'plot', 'commercial', 'office'];
            if (in_array($this->currentFilters['propertyType'], $allowedTypes)) {
                $query->where('property_type', $this->currentFilters['propertyType']);
            }
        }

        // Validate and sanitize minPrice
        $minPrice = $this->validateNumeric($this->currentFilters['minPrice'] ?? null);
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }

        // Validate and sanitize maxPrice
        $maxPrice = $this->validateNumeric($this->currentFilters['maxPrice'] ?? null);
        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        // Validate configuration array
        $validatedConfigs = $this->validateNumericArray($this->currentFilters['configuration'] ?? []);
        if (!empty($validatedConfigs)) {
            $query->whereIn('bedrooms', $validatedConfigs);
        }

        if (!empty($this->currentFilters['carpetArea'])) {
            // Apply area filter based on the selected range
            switch ($this->currentFilters['carpetArea']) {
                case '500':
                    $query->where('area_sqft', '<=', 500);
                    break;
                case '1000':
                    $query->whereBetween('area_sqft', [500, 1000]);
                    break;
                case '1500':
                    $query->whereBetween('area_sqft', [1000, 1500]);
                    break;
                case '2000':
                    $query->where('area_sqft', '>=', 1500);
                    break;
            }
        }

        if (!empty($this->currentFilters['floorPreference'])) {
            // This might need additional logic based on how floors are stored
        }

        if (!empty($this->currentFilters['bathrooms'])) {
            $validatedBathrooms = $this->validateNumeric($this->currentFilters['bathrooms']);
            if ($validatedBathrooms !== null) {
                $query->where('bathrooms', $validatedBathrooms);
            }
        }

        if (!empty($this->currentFilters['furnishing'])) {
            // Validate furnishing is one of allowed values
            $allowedFurnishing = ['unfurnished', 'semifurnished', 'fullyfurnished'];
            if (in_array($this->currentFilters['furnishing'], $allowedFurnishing)) {
                $query->where('furnishing', $this->currentFilters['furnishing']);
            }
        }

        return $query;
    }

    public function applyFilters($filters)
    {
        // Sanitize and validate all filter values
        $sanitizedFilters = [];
        
        if (isset($filters['propertyType'])) {
            $allowedTypes = ['apartment', 'villa', 'plot', 'commercial', 'office'];
            $sanitizedFilters['propertyType'] = in_array($filters['propertyType'], $allowedTypes) 
                ? $filters['propertyType'] : '';
        }
        
        $sanitizedFilters['minPrice'] = $this->validateNumeric($filters['minPrice'] ?? null);
        $sanitizedFilters['maxPrice'] = $this->validateNumeric($filters['maxPrice'] ?? null);
        $sanitizedFilters['configuration'] = $this->validateNumericArray($filters['configuration'] ?? []);
        $sanitizedFilters['carpetArea'] = isset($filters['carpetArea']) ? preg_replace('/[^0-9]/', '', $filters['carpetArea']) : '';
        $sanitizedFilters['bathrooms'] = $this->validateNumeric($filters['bathrooms'] ?? null);
        
        $this->currentFilters = $sanitizedFilters;
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->currentFilters = [
            'propertyType' => '',
            'minPrice' => '',
            'maxPrice' => '',
            'configuration' => [],
            'carpetArea' => '',
            'floorPreference' => '',
            'bathrooms' => '',
            'furnishing' => '',
        ];
        $this->resetPage();
    }

    public function applySearch($search)
    {
        // Sanitize search input before storing
        $this->searchQuery = $this->sanitizeSearchInput($search);
        $this->resetPage();
    }

    public function applyCategory($category)
    {
        $this->categoryFilter = $category;
        $this->resetPage();
    }

    /**
     * Handle sort changes
     */
    public function updatedSortBy($value)
    {
        return redirect()->route('home', ['sort' => $value]);
    }

    public function getWhatsAppMessage($property)
    {
        $domain = request()->getHost();
        $agencyName = $this->settings['agency_name'] ?? 'Elite Homes';
        $message = "Hii i'm interested in\n*{$property['title']}*\nat {$property['address']}\nUID: {$property['id']}\nLink: {$domain}/property/{$property['id']}";
        $encodedMessage = urlencode($message);
        $settings = $this->settings ?? [];
        $phone = preg_replace('/[^\d]/', '', $settings['w_no'] ?? '');
        return $phone ? "https://wa.me/{$phone}?text={$encodedMessage}" : '#';
    }

    public function getPropertyBadge($property)
    {
        $badges = [
            'new' => ['label' => 'New', 'color' => 'bg-blue-500'],
            'popular' => ['label' => 'Popular', 'color' => 'bg-orange-500'],
            'verified' => ['label' => 'Verified', 'color' => 'bg-teal-600'],
            'featured' => ['label' => 'Featured', 'color' => 'bg-purple-500'],
        ];

        $labelType = $property['label_type'] ?? null;

        if ($labelType === 'custom') {
            return [
                'label' => 'Custom',
                'color' => $property['custom_label_color'] ?? '#gray'
            ];
        }

        return $badges[$labelType] ?? null;
    }

    public function render()
    {
        return view('livewire.public.listings', [
            'properties' => $this->properties,
        ]);
    }
}
