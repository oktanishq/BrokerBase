<?php

namespace App\Livewire\Public;

use Livewire\Component;

class Filters extends Component
{
    public $search = '';
    public $category = 'all';
    public $showFilters = false;
    public $selectedCategory = 'propertyType';
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
        ['key' => 'propertyType', 'name' => 'Property Type', 'icon' => 'home'],
        ['key' => 'priceRange', 'name' => 'Price Range', 'icon' => 'attach_money'],
        ['key' => 'configuration', 'name' => 'Configuration', 'icon' => 'meeting_room'],
        ['key' => 'carpetArea', 'name' => 'Carpet Area', 'icon' => 'square_foot'],
        ['key' => 'floorPreference', 'name' => 'Floor Preference', 'icon' => 'stairs'],
        ['key' => 'bathrooms', 'name' => 'Bathrooms', 'icon' => 'bathtub'],
        ['key' => 'furnishing', 'name' => 'Furnishing', 'icon' => 'star'],
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
    }

    public function render()
    {
        return view('livewire.public.filters');
    }
}
