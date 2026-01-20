<?php

namespace App\Livewire\Public;

use Livewire\Component;

class Filters extends Component
{
    public $search = '';
    public $category = 'all';
    public $showFilters = false;
    public $selectedCategory = 'propertyType';
    public $expandedCategories = ['propertyType', 'priceRange']; // For accordion
    public $filterSearches = [
        'propertyType' => '',
        'configuration' => '',
        'carpetArea' => '',
        'floorPreference' => '',
        'bathrooms' => '',
        'furnishing' => '',
    ];
    public $savedFilters = [];
    public $currentSavedFilter = null;
    public $showSaveDialog = false;
    public $savedFilterName = '';
    public $showSavedDropdown = false;
    public $filters = [
        'propertyType' => '',
        'minPrice' => '',
        'maxPrice' => '',
        'configuration' => [],
        'carpetArea' => '',
        'floorPreference' => '',
        'bathrooms' => '',
        'furnishing' => '',
    ];

    public $filterCategories = [
        ['key' => 'propertyType', 'name' => 'Property Type', 'icon' => 'home', 'description' => 'Choose the type of property you\'re looking for'],
        ['key' => 'priceRange', 'name' => 'Price Range', 'icon' => 'attach_money', 'description' => 'Set your budget range'],
        ['key' => 'configuration', 'name' => 'Configuration', 'icon' => 'meeting_room', 'description' => 'Select bedroom configuration'],
        ['key' => 'carpetArea', 'name' => 'Carpet Area', 'icon' => 'square_foot', 'description' => 'Choose property size'],
        ['key' => 'floorPreference', 'name' => 'Floor Preference', 'icon' => 'stairs', 'description' => 'Preferred floor level'],
        ['key' => 'bathrooms', 'name' => 'Bathrooms', 'icon' => 'bathtub', 'description' => 'Number of bathrooms'],
        ['key' => 'furnishing', 'name' => 'Furnishing', 'icon' => 'star', 'description' => 'Furnishing status'],
    ];

    public $propertyTypes = [
        ['value' => 'apartment', 'label' => 'Apartment'],
        ['value' => 'villa', 'label' => 'Villa'],
        ['value' => 'plot', 'label' => 'Plot'],
        ['value' => 'commercial', 'label' => 'Commercial'],
    ];

    public $priceOptions = [
        ['value' => '100000', 'label' => '1 Lac'],
        ['value' => '500000', 'label' => '5 Lacs'],
        ['value' => '1000000', 'label' => '10 Lacs'],
        ['value' => '2000000', 'label' => '20 Lacs'],
        ['value' => '5000000', 'label' => '50 Lacs'],
        ['value' => '10000000', 'label' => '1 Cr'],
    ];

    public $configurations = [
        ['value' => '1bhk', 'label' => '1 BHK'],
        ['value' => '2bhk', 'label' => '2 BHK'],
        ['value' => '3bhk', 'label' => '3 BHK'],
        ['value' => '4bhk', 'label' => '4+ BHK'],
    ];

    public $carpetAreas = [
        ['value' => '500', 'label' => 'Up to 500 sqft'],
        ['value' => '1000', 'label' => '500 - 1000 sqft'],
        ['value' => '1500', 'label' => '1000 - 1500 sqft'],
        ['value' => '2000', 'label' => '1500+ sqft'],
    ];

    public $floorOptions = [
        ['value' => 'ground', 'label' => 'Ground Floor'],
        ['value' => '1-5', 'label' => '1st to 5th Floor'],
        ['value' => '6-10', 'label' => '6th to 10th Floor'],
        ['value' => '11+', 'label' => '11th Floor & Above'],
    ];

    public $bathroomOptions = [
        ['value' => '1', 'label' => '1 Bathroom'],
        ['value' => '2', 'label' => '2 Bathrooms'],
        ['value' => '3', 'label' => '3+ Bathrooms'],
    ];

    public $furnishingOptions = [
        ['value' => 'unfurnished', 'label' => 'Unfurnished'],
        ['value' => 'semifurnished', 'label' => 'Semi-Furnished'],
        ['value' => 'fullyfurnished', 'label' => 'Fully Furnished'],
    ];

    public function mount()
    {
        // Initialize if needed
    }

    public function openFilters()
    {
        $this->showFilters = true;
    }

    public function closeFilters()
    {
        $this->showFilters = false;
    }

    public function selectCategory($category)
    {
        $this->selectedCategory = $category;
    }

    public function applyFilters()
    {
        $this->dispatch('filtersApplied', $this->filters);
        $this->closeFilters();
    }

    public function resetFilters()
    {
        $this->filters = [
            'propertyType' => '',
            'minPrice' => '',
            'maxPrice' => '',
            'configuration' => [],
            'carpetArea' => '',
            'floorPreference' => '',
            'bathrooms' => '',
            'furnishing' => '',
        ];
        $this->selectedCategory = 'propertyType';
        $this->dispatch('filtersReset');
        $this->closeFilters();
    }

    public function resetCurrentCategory()
    {
        switch ($this->selectedCategory) {
            case 'propertyType':
                $this->filters['propertyType'] = '';
                break;
            case 'priceRange':
                $this->filters['minPrice'] = '';
                $this->filters['maxPrice'] = '';
                break;
            case 'configuration':
                $this->filters['configuration'] = [];
                break;
            case 'carpetArea':
                $this->filters['carpetArea'] = '';
                break;
            case 'floorPreference':
                $this->filters['floorPreference'] = '';
                break;
            case 'bathrooms':
                $this->filters['bathrooms'] = '';
                break;
            case 'furnishing':
                $this->filters['furnishing'] = '';
                break;
        }
    }

    public function getFilterCount($category)
    {
        switch ($category) {
            case 'propertyType':
                return $this->filters['propertyType'] ? 1 : 0;
            case 'priceRange':
                return ($this->filters['minPrice'] || $this->filters['maxPrice']) ? 1 : 0;
            case 'configuration':
                return count($this->filters['configuration']);
            case 'carpetArea':
                return $this->filters['carpetArea'] ? 1 : 0;
            case 'floorPreference':
                return $this->filters['floorPreference'] ? 1 : 0;
            case 'bathrooms':
                return $this->filters['bathrooms'] ? 1 : 0;
            case 'furnishing':
                return $this->filters['furnishing'] ? 1 : 0;
            default:
                return 0;
        }
    }

    public function getActiveFilterCount()
    {
        $count = 0;
        foreach ($this->filterCategories as $cat) {
            $count += $this->getFilterCount($cat['key']);
        }
        return $count;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function updatedSearch()
    {
        $this->dispatch('searchUpdated', $this->search);
    }

    public function updatedCategory()
    {
        $this->dispatch('categoryUpdated', $this->category);
        $this->trackAnalytics('category_changed', ['category' => $this->category]);
    }

    public function toggleCategory($categoryKey)
    {
        if (in_array($categoryKey, $this->expandedCategories)) {
            $this->expandedCategories = array_diff($this->expandedCategories, [$categoryKey]);
        } else {
            $this->expandedCategories[] = $categoryKey;
        }
    }

    public function applyAndStay()
    {
        $this->dispatch('filtersApplied', $this->filters);
        $this->trackAnalytics('filters_applied_stay', $this->filters);
        // Don't close modal
    }

    public function applyAndClose()
    {
        $this->dispatch('filtersApplied', $this->filters);
        $this->closeFilters();
        $this->trackAnalytics('filters_applied_close', $this->filters);
    }

    public function saveCurrentFilters()
    {
        $this->showSaveDialog = true;
    }

    public function saveFilter()
    {
        if (!empty($this->savedFilterName)) {
            $this->savedFilters[$this->savedFilterName] = $this->filters;
            $this->savedFilterName = '';
            $this->showSaveDialog = false;
            $this->trackAnalytics('filter_saved', ['name' => $this->savedFilterName]);
        }
    }

    public function loadSavedFilter($name)
    {
        if (isset($this->savedFilters[$name])) {
            $this->filters = $this->savedFilters[$name];
            $this->trackAnalytics('filter_loaded', ['name' => $name]);
        }
    }

    public function deleteSavedFilter($name)
    {
        unset($this->savedFilters[$name]);
        $this->trackAnalytics('filter_deleted', ['name' => $name]);
    }

    public function getFilteredOptions($category, $options)
    {
        $search = $this->filterSearches[$category] ?? '';
        if (empty($search)) {
            return $options;
        }

        return array_filter($options, function($option) use ($search) {
            return stripos($option['label'], $search) !== false;
        });
    }

    public function getAppliedFiltersSummary()
    {
        $summary = [];

        if (!empty($this->filters['propertyType'])) {
            $type = collect($this->propertyTypes)->firstWhere('value', $this->filters['propertyType']);
            $summary[] = ['key' => 'propertyType', 'label' => 'Type: ' . ($type['label'] ?? $this->filters['propertyType'])];
        }

        if (!empty($this->filters['minPrice']) || !empty($this->filters['maxPrice'])) {
            $priceText = '';
            if (!empty($this->filters['minPrice'])) $priceText .= '₹' . number_format($this->filters['minPrice']);
            $priceText .= ' - ';
            if (!empty($this->filters['maxPrice'])) $priceText .= '₹' . number_format($this->filters['maxPrice']);
            $summary[] = ['key' => 'priceRange', 'label' => trim($priceText, ' - ')];
        }

        if (!empty($this->filters['configuration'])) {
            $configs = collect($this->configurations)->whereIn('value', $this->filters['configuration'])->pluck('label');
            $summary[] = ['key' => 'configuration', 'label' => 'BHK: ' . $configs->join(', ')];
        }

        if (!empty($this->filters['carpetArea'])) {
            $area = collect($this->carpetAreas)->firstWhere('value', $this->filters['carpetArea']);
            $summary[] = ['key' => 'carpetArea', 'label' => 'Area: ' . ($area['label'] ?? $this->filters['carpetArea'])];
        }

        if (!empty($this->filters['floorPreference'])) {
            $floor = collect($this->floorOptions)->firstWhere('value', $this->filters['floorPreference']);
            $summary[] = ['key' => 'floorPreference', 'label' => 'Floor: ' . ($floor['label'] ?? $this->filters['floorPreference'])];
        }

        if (!empty($this->filters['bathrooms'])) {
            $bath = collect($this->bathroomOptions)->firstWhere('value', $this->filters['bathrooms']);
            $summary[] = ['key' => 'bathrooms', 'label' => 'Bathrooms: ' . ($bath['label'] ?? $this->filters['bathrooms'])];
        }

        if (!empty($this->filters['furnishing'])) {
            $furnish = collect($this->furnishingOptions)->firstWhere('value', $this->filters['furnishing']);
            $summary[] = ['key' => 'furnishing', 'label' => 'Furnishing: ' . ($furnish['label'] ?? $this->filters['furnishing'])];
        }

        return $summary;
    }

    public function removeFilter($key)
    {
        switch ($key) {
            case 'propertyType':
                $this->filters['propertyType'] = '';
                break;
            case 'priceRange':
                $this->filters['minPrice'] = '';
                $this->filters['maxPrice'] = '';
                break;
            case 'configuration':
                $this->filters['configuration'] = [];
                break;
            case 'carpetArea':
                $this->filters['carpetArea'] = '';
                break;
            case 'floorPreference':
                $this->filters['floorPreference'] = '';
                break;
            case 'bathrooms':
                $this->filters['bathrooms'] = '';
                break;
            case 'furnishing':
                $this->filters['furnishing'] = '';
                break;
        }
        $this->trackAnalytics('filter_removed', ['key' => $key]);
    }

    private function trackAnalytics($event, $data = [])
    {
        // Analytics integration - could send to Google Analytics, Mixpanel, etc.
        // For now, just log to console in development
        if (app()->environment('local')) {
            logger("Filter Analytics: {$event}", $data);
        }
    }

    public function render()
    {
        return view('livewire.public.filters');
    }
}
