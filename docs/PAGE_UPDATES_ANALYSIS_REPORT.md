# BrokerBase Page Updates Analysis Report

**Document Version:** 1.0  
**Date:** January 2026  
**Prepared For:** BrokerBase Development Team  
**Scope:** Analysis and Recommendations for Three Key Page Updates

---

## Table of Contents

1. [Dashboard Page Analysis & Recommendations](#1-dashboard-page-analysis--recommendations)
2. [Inventory Page Analysis & Recommendations](#2-inventory-page-analysis--recommendations)
3. [Homepage Analysis & Recommendations](#3-homepage-analysis--recommendations)
4. [Implementation Priority Matrix](#4-implementation-priority-matrix)
5. [Conclusion](#5-conclusion)

---

## 1. Dashboard Page Analysis & Recommendations

### Current State Analysis

The dashboard page at [`laravel/resources/views/admin/dashboard.blade.php`](laravel/resources/views/admin/dashboard.blade.php) currently exhibits several issues that limit its effectiveness:

#### Hardcoded Statistics
The page contains static values for key performance indicators:

```blade
<!-- Current implementation - Line 22 -->
<p class="text-2xl font-bold text-gray-900">24</p>

<!-- Current implementation - Line 38 -->
<p class="text-2xl font-bold text-gray-900">18</p>

<!-- Current implementation - Line 54 -->
<p class="text-2xl font-bold text-gray-900">6</p>
```

These hardcoded values mean the dashboard does not reflect actual business data, rendering it ineffective for decision-making.

#### Static Recent Listings Table

The recent listings section (lines 84-177) contains three hardcoded property entries with:
- Static images from external URLs
- Fixed prices and addresses
- Pre-defined status badges
- No connection to the database

### Why This Approach is Recommended

Implementing real data fetching for the dashboard provides:

1. **Real-Time Visibility**: Administrators can see actual inventory counts, active listings, and sales data
2. **Data-Driven Decisions**: Percentage changes and trends become meaningful metrics
3. **Scalability**: As the business grows, the dashboard automatically reflects current data
4. **Reduced Maintenance**: No manual updates required when properties are added or sold

### How to Implement with Code Examples

#### Option A: Simple PHP Controller Approach (Recommended for Dashboard)

Create a dashboard controller method that fetches real data:

```php
<?php
// File: laravel/app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $dealerId = Auth::id();
        
        // Get stats for the authenticated dealer
        $stats = [
            'totalProperties' => Property::where('user_id', $dealerId)->count(),
            'activeListings' => Property::where('user_id', $dealerId)
                ->where('status', 'available')
                ->count(),
            'propertiesSold' => Property::where('user_id', $dealerId)
                ->where('status', 'sold')
                ->count(),
            'totalViews' => Property::where('user_id', $dealerId)
                ->sum('view_count'),
        ];
        
        // Calculate percentage changes from last month
        $thisMonth = now()->month;
        $lastMonth = now()->subMonth()->month;
        
        $stats['totalPropertiesChange'] = $this->calculatePercentageChange(
            Property::where('user_id', $dealerId)
                ->whereMonth('created_at', $lastMonth)
                ->count(),
            Property::where('user_id', $dealerId)
                ->whereMonth('created_at', $thisMonth)
                ->count()
        );
        
        // Get recent listings (limit 5)
        $recentListings = Property::where('user_id', $dealerId)
            ->with('images')
            ->latest()
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact('stats', 'recentListings'));
    }
    
    private function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return $newValue > 0 ? 100 : 0;
        }
        return round((($newValue - $oldValue) / $oldValue) * 100, 1);
    }
}
```

#### Updated Dashboard Blade Template

```blade
{{-- File: laravel/resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'BrokerBase - Dealer Dashboard')

@section('header-content')
<h2 class="text-slate-900 text-lg font-bold leading-tight">Welcome back, {{ Auth::user()->name ?? 'Dealer' }}</h2>
<p class="text-sm text-gray-500 hidden sm:block">Here's what's happening today.</p>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <h1 class="text-slate-900 text-3xl font-black leading-tight tracking-tight">Dashboard</h1>
    <x-admin.AddPropertyModal />
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Total Properties Card -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total Properties</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['totalProperties']) }}</p>
            </div>
            <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-blue-600">warehouse</span>
            </div>
        </div>
        @if(isset($stats['totalPropertiesChange']))
        <div class="mt-4 flex items-center text-sm">
            <span class="{{ $stats['totalPropertiesChange'] >= 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                {{ $stats['totalPropertiesChange'] >= 0 ? '+' : '' }}{{ $stats['totalPropertiesChange'] }}%
            </span>
            <span class="text-gray-500 ml-1">from last month</span>
        </div>
        @endif
    </div>

    <!-- Active Listings Card -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Active Listings</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['activeListings']) }}</p>
            </div>
            <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-green-600">visibility</span>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-green-600 font-medium">Live</span>
            <span class="text-gray-500 ml-1">currently visible</span>
        </div>
    </div>

    <!-- Properties Sold Card -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Properties Sold</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['propertiesSold']) }}</p>
            </div>
            <div class="h-12 w-12 bg-amber-100 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-amber-600">check_circle</span>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-amber-600 font-medium">Total</span>
            <span class="text-gray-500 ml-1">all time</span>
        </div>
    </div>
</div>

<!-- Recent Listings Table with Real Data -->
<div class="flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden -mt-4">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-slate-900">Recent Listings</h3>
        <a class="text-sm text-royal-blue font-medium hover:underline" href="{{ route('admin.inventory') }}">View All</a>
    </div>
    
    @if($recentListings && $recentListings->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full min-w-[700px]">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Property</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Listed</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($recentListings as $property)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-4">
                            @php
                            $primaryImage = $property->images->where('is_primary', true)->first() 
                                ?? $property->images->first();
                            @endphp
                            <div class="w-16 h-12 rounded-lg bg-cover bg-center shrink-0" 
                                 style="background-image: url('{{ $primaryImage ? asset('storage/' . $primaryImage->path) : asset('images/placeholder.jpg') }}')">
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-900">{{ $property->title }}</span>
                                <span class="text-xs text-gray-500">{{ $property->address }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-bold text-royal-blue">${{ number_format($property->price) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                        $statusClasses = [
                            'available' => 'bg-emerald-100 text-emerald-800',
                            'sold' => 'bg-rose-100 text-rose-800',
                            'draft' => 'bg-gray-100 text-gray-600',
                        ];
                        $statusClass = $statusClasses[$property->status] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                            <span class="size-1.5 rounded-full {{ $property->status == 'available' ? 'bg-emerald-600' : ($property->status == 'sold' ? 'bg-rose-600' : 'bg-gray-500') }}"></span>
                            {{ ucfirst($property->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-500">{{ $property->created_at->diffForHumans() }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.properties.edit', $property->id) }}" 
                               class="p-2 text-gray-400 hover:text-royal-blue hover:bg-blue-50 rounded-full transition-colors">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <button class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-full transition-colors">
                                <span class="material-symbols-outlined text-[20px]">share</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="p-6 text-center text-gray-500">
        No properties listed yet. <a href="#" class="text-royal-blue hover:underline">Add your first property</a>
    </div>
    @endif
</div>
@endsection
```

### File Structure Recommendations

```
laravel/
├── app/
│   └── Http/
│       └── Controllers/
││               └── DashboardController.php            └── Admin/
 [NEW]
├── routes/
│   └── web.php  [UPDATE - add route]
└── resources/
    └── views/
        └── admin/
            └── dashboard.blade.php  [UPDATE]
```

---

## 2. Inventory Page Analysis & Recommendations

### Current State Analysis

The inventory page at [`laravel/resources/views/admin/inventory.blade.php`](laravel/resources/views/admin/inventory.blade.php) serves as a wrapper for the Livewire inventory component. Current implementation includes:

#### Strengths
- Already uses Livewire for dynamic data handling
- Has search and filter functionality
- Supports both list and grid view modes
- Includes pagination controls

#### Identified Gaps

1. **Missing Sorting Functionality**
   - No option to sort by date, price, or status
   - Users must manually scan through pages to find relevant data

2. **Limited Edit Modal Functionality**
   - Current modal lacks image management features
   - No ability to reorder property images
   - No bulk action capabilities

3. **Tailwind Configuration in View**
   - Lines 10-28 contain inline Tailwind config that should be in the main config

### Why This Approach is Recommended

Enhancing the inventory page with sorting, better image management, and bulk actions will:

1. **Improve User Efficiency**: Sorting allows quick access to recently added or priced properties
2. **Better Image Presentation**: Image reordering helps showcase properties effectively
3. **Streamlined Bulk Operations**: Bulk actions reduce repetitive tasks for administrators
4. **Professional UX**: Modern features align with industry standards

### How to Implement with Code Examples

#### Enhanced Inventory Livewire Component with Sorting

```php
<?php
// File: laravel/app/Livewire/Admin/Inventory.php

namespace App\Livewire\Admin;

use App\Models\Property;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Inventory extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $statusFilter = 'all';
    public $typeFilter = 'all';
    public $viewMode = 'list'; // 'list' or 'grid'
    public $perPage = 10;
    
    // NEW: Sorting
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $listeners = [
        'property-updated' => 'handlePropertyUpdate',
        'property-deleted' => 'handlePropertyDeleted',
        'open-edit-modal' => 'forwardToEditModal',
        'open-delete-modal' => 'forwardToDeleteModal',
        'bulk-action' => 'handleBulkAction',
    ];

    /**
     * Toggle sort direction or change sort field
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Get sort icon based on current sort state
     */
    public function getSortIcon($field)
    {
        if ($this->sortField !== $field) {
            return 'unfold_more';
        }
        return $this->sortDirection === 'asc' ? 'expand_less' : 'expand_more';
    }

    public function getPropertiesProperty()
    {
        $query = Property::where('user_id', Auth::id());

        // Apply search
        if ($this->searchTerm) {
            $query->where(function ($q) {
                if (is_numeric($this->searchTerm)) {
                    $q->where('id', $this->searchTerm);
                }
                $q->where('title', 'ILIKE', '%' . $this->searchTerm . '%')
                  ->orWhere('address', 'ILIKE', '%' . $this->searchTerm . '%');
            });
        }

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Apply type filter
        if ($this->typeFilter !== 'all') {
            $query->where('property_type', $this->typeFilter);
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    /**
     * Handle bulk actions
     */
    public function handleBulkAction($action, $selectedIds)
    {
        if (empty($selectedIds)) {
            session()->flash('error', 'No properties selected.');
            return;
        }

        $properties = Property::whereIn('id', $selectedIds)
            ->where('user_id', Auth::id());

        switch ($action) {
            case 'mark_available':
                $properties->update(['status' => 'available']);
                session()->flash('success', count($selectedIds) . ' properties marked as available.');
                break;
            case 'mark_sold':
                $properties->update(['status' => 'sold']);
                session()->flash('success', count($selectedIds) . ' properties marked as sold.');
                break;
            case 'delete':
                $properties->delete();
                session()->flash('success', count($selectedIds) . ' properties deleted.');
                break;
        }
    }

    // ... existing methods
}
```

#### Enhanced Inventory View with Sorting Headers

```blade
{{-- File: laravel/resources/views/livewire/admin/inventory.blade.php --}}
<div x-data @property-updated.window="$wire.call('$refresh')">
    <!-- Bulk Actions Bar - NEW -->
    <div x-show="$wire.selectedProperties.length > 0" 
         x-transition
         class="fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-white rounded-xl shadow-lg border border-gray-200 p-4 flex items-center gap-4 z-50">
        <span class="text-sm font-medium text-gray-700">
            <span x-text="$wire.selectedProperties.length"></span> selected
        </span>
        <button @click="$wire.dispatch('bulk-action', { action: 'mark_available', ids: $wire.selectedProperties })"
                class="px-3 py-1.5 bg-green-100 text-green-700 text-sm font-medium rounded-lg hover:bg-green-200 transition-colors">
            Mark Available
        </button>
        <button @click="$wire.dispatch('bulk-action', { action: 'mark_sold', ids: $wire.selectedProperties })"
                class="px-3 py-1.5 bg-amber-100 text-amber-700 text-sm font-medium rounded-lg hover:bg-amber-200 transition-colors">
            Mark Sold
        </button>
        <button @click="$wire.dispatch('bulk-action', { action: 'delete', ids: $wire.selectedProperties })"
                class="px-3 py-1.5 bg-red-100 text-red-700 text-sm font-medium rounded-lg hover:bg-red-200 transition-colors">
            Delete
        </button>
        <button @click="$wire.selectedProperties = []"
                class="px-3 py-1.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            Clear
        </button>
    </div>

    <!-- Search and Filter Bar -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 items-center justify-between sticky top-0 z-10 mb-8">
        <!-- Search Input -->
        <div class="relative w-full md:w-96 group">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                <span class="material-symbols-outlined">search</span>
            </span>
            <input wire:model.live.debounce.300ms="searchTerm" 
                   class="w-full py-2.5 pl-10 pr-4 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                   placeholder="Search by id, title, location..." type="text"/>
        </div>

        <!-- Filters and View Toggle -->
        <div class="flex flex-col sm:flex-row w-full md:w-auto items-center gap-3 justify-between md:justify-end">
            <!-- Sort Dropdown - NEW -->
            <div class="relative" x-data="{ sortOpen: false }">
                <button @click="sortOpen = !sortOpen; typeOpen = false; statusOpen = false"
                        class="flex items-center justify-between w-full sm:w-44 px-4 py-2.5 rounded-xl border-2 border-slate-200 bg-gradient-to-r from-white to-slate-50 hover:from-slate-50 hover:to-slate-100 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-blue-500/20 text-slate-700 font-semibold shadow-sm">
                    <span class="flex items-center space-x-2 text-sm">
                        <span class="text-slate-500">Sort:</span>
                        <span>{{ ucfirst(str_replace('_', ' ', $sortField)) }} ({{ $sortDirection === 'asc' ? 'A-Z' : 'Z-A' }})</span>
                    </span>
                    <span class="material-symbols-outlined text-slate-400 text-lg transition-transform duration-300" :class="sortOpen ? 'rotate-180' : ''">expand_more</span>
                </button>

                <div x-show="sortOpen" x-transition class="absolute top-full right-0 mt-2 w-48 bg-white border-2 border-slate-200 rounded-xl shadow-xl shadow-slate-900/10 overflow-hidden z-20">
                    <ul class="py-2">
                        @foreach(['created_at' => 'Date Listed', 'price' => 'Price', 'title' => 'Title', 'status' => 'Status'] as $field => $label)
                        <li>
                            <button @click="$wire.sortBy('{{ $field }}'); sortOpen = false"
                                    class="w-full text-left px-4 py-3 text-sm flex items-center justify-between hover:bg-slate-50 transition-colors">
                                <span>{{ $label }}</span>
                                @if($sortField === $field)
                                <span class="material-symbols-outlined text-blue-600 text-base">
                                    {{ $sortDirection === 'asc' ? 'expand_less' : 'expand_more' }}
                                </span>
                                @endif
                            </button>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="relative" x-data="{ statusOpen: false }">
                <!-- ... existing status filter code ... -->
            </div>

            <!-- Type Filter -->
            <div class="relative" x-data="{ typeOpen: false }">
                <!-- ... existing type filter code ... -->
            </div>

            <!-- View Toggle -->
            <div class="flex items-center bg-gray-50 rounded-lg p-1 border border-gray-200">
                <button wire:click="$set('viewMode', 'grid')"
                        class="p-1.5 rounded transition-all {{ $viewMode === 'grid' ? 'bg-white shadow-sm text-royal-blue' : 'text-gray-400 hover:text-gray-600' }}">
                    <span class="material-symbols-outlined text-[22px]">grid_view</span>
                </button>
                <button wire:click="$set('viewMode', 'list')"
                        class="p-1.5 rounded transition-all {{ $viewMode === 'list' ? 'bg-white shadow-sm text-royal-blue' : 'text-gray-400 hover:text-gray-600' }}">
                    <span class="material-symbols-outlined text-[22px]">view_list</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Property List -->
    @if($this->properties->count() > 0)
        <!-- Select All Checkbox - NEW -->
        <div class="flex items-center gap-2 mb-4">
            <input type="checkbox" 
                   wire:model.live="selectAll"
                   class="w-4 h-4 text-royal-blue border-gray-300 rounded focus:ring-royal-blue">
            <label class="text-sm text-gray-600">Select All</label>
        </div>

        <div class="mt-8">
            <!-- List View -->
            @if($viewMode === 'list')
                <div class="flex flex-col gap-4">
                    @foreach($this->properties as $property)
                        @livewire('admin.inventory.property-list-card', ['property' => $property], key('list-' . $property->id))
                    @endforeach
                </div>
            @endif

            <!-- Grid View -->
            @if($viewMode === 'grid')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($this->properties as $property)
                        @livewire('admin.inventory.property-grid-card', ['property' => $property], key('grid-' . $property->id))
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <!-- Pagination -->
    @if($this->properties->count() > 0)
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-t border-gray-200 pt-6 mt-4">
            <!-- Items per page -->
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-500">Show:</label>
                <select wire:model.live="perPage" class="py-1 px-2 text-sm border border-gray-200 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
                <span class="text-sm text-gray-500">per page</span>
            </div>

            <!-- Pagination controls -->
            <div class="flex items-center gap-2">
                {{ $this->properties->links() }}
            </div>
        </div>
    @endif
</div>
```

#### Enhanced Edit Modal with Image Management

```php
<?php
// File: laravel/app/Livewire/Admin/Inventory/EditPropertyModal.php

namespace App\Livewire\Admin\Inventory;

use App\Models\Property;
use App\Models\PropertyImage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class EditPropertyModal extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $propertyId = null;
    public $property = [];
    public $newImages = [];
    public $imageOrder = [];
    public $deletedImages = [];

    protected $listeners = ['open-edit-modal'];

    public function openEditModal($propertyData)
    {
        if (is_array($propertyData) && isset($propertyData['id'])) {
            $this->propertyId = $propertyData['id'];
            $this->loadProperty();
            $this->showModal = true;
        }
    }

    public function loadProperty()
    {
        $property = Property::with('images')->find($this->propertyId);
        
        if ($property) {
            $this->property = $property->toArray();
            $this->imageOrder = $property->images->pluck('id')->toArray();
        }
    }

    /**
     * Reorder images via drag and drop
     */
    public function updateImageOrder($newOrder)
    {
        $this->imageOrder = $newOrder;
    }

    /**
     * Upload new images
     */
    public function updatedNewImages()
    {
        foreach ($this->newImages as $image) {
            $path = $image->store('property-images', 'public');
            
            PropertyImage::create([
                'property_id' => $this->propertyId,
                'path' => $path,
                'is_primary' => empty($this->imageOrder),
            ]);
            
            $this->imageOrder[] = PropertyImage::latest()->first()->id;
        }
        
        $this->loadProperty();
        $this->newImages = [];
    }

    /**
     * Mark image for deletion
     */
    public function markImageForDeletion($imageId)
    {
        $this->deletedImages[] = $imageId;
    }

    /**
     * Remove image from deletion list
     */
    public function removeFromDeletion($imageId)
    {
        $this->deletedImages = array_diff($this->deletedImages, [$imageId]);
    }

    /**
     * Confirm and delete marked images
     */
    public function confirmDeleteImages()
    {
        foreach ($this->deletedImages as $imageId) {
            $image = PropertyImage::find($imageId);
            if ($image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
        }
        
        $this->deletedImages = [];
        $this->loadProperty();
        $this->dispatch('property-updated', $this->propertyId, $this->property);
    }

    /**
     * Set primary image
     */
    public function setPrimaryImage($imageId)
    {
        PropertyImage::where('property_id', $this->propertyId)->update(['is_primary' => false]);
        PropertyImage::find($imageId)->update(['is_primary' => true]);
        $this->loadProperty();
    }

    /**
     * Save property changes
     */
    public function save()
    {
        Property::find($this->propertyId)->update($this->property);
        
        // Update image order
        foreach ($this->imageOrder as $index => $imageId) {
            PropertyImage::where('id', $imageId)->update(['order' => $index]);
        }
        
        $this->showModal = false;
        $this->dispatch('property-updated', $this->propertyId, $this->property);
    }

    public function render()
    {
        return view('livewire.admin.inventory.edit-property-modal');
    }
}
```

```blade
{{-- File: laravel/resources/views/livewire/admin/inventory/edit-property-modal.blade.php --}}
<div x-data="{ 
    show: @entangle('showModal'),
    draggedImage: null,
    dropZoneActive: false
}" 
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto">
    
    <!-- Backdrop -->
    <div x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="$wire.showModal = false"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>

    <!-- Modal -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-4"
             class="relative w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden">
            
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <h3 class="text-xl font-bold text-slate-900">Edit Property</h3>
                <button @click="$wire.showModal = false" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <!-- Content -->
            <div class="p-6 max-h-[70vh] overflow-y-auto">
                <!-- Image Management Section -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-slate-900 mb-4">Property Images</h4>
                    
                    <!-- Existing Images with Reorder -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        @foreach($property['images'] ?? [] as $image)
                        @if(!in_array($image['id'], $deletedImages))
                        <div class="relative group {{ $image['is_primary'] ? 'ring-2 ring-blue-500' : '' }}"
                             draggable="true"
                             @dragstart="draggedImage = {{ $image['id'] }}"
                             @dragover.prevent
                             @drop="updateImageOrder({{ $image['id'] }})">
                            
                            <img src="{{ asset('storage/' . $image['path']) }}" 
                                 alt="Property image" 
                                 class="w-full h-32 object-cover rounded-lg">
                            
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center gap-2">
                                <button @click="setPrimaryImage({{ $image['id'] }})"
                                        class="p-2 bg-white rounded-full hover:bg-gray-100"
                                        title="Set as primary">
                                    <span class="material-symbols-outlined text-sm">star</span>
                                </button>
                                <button wire:click="markImageForDeletion({{ $image['id'] }})"
                                        class="p-2 bg-white rounded-full hover:bg-red-100 text-red-600"
                                        title="Delete">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                            </div>
                            
                            @if($image['is_primary'])
                            <span class="absolute top-2 left-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">Primary</span>
                            @endif
                        </div>
                        @endif
                        @endforeach
                    </div>
                    
                    <!-- Deleted Images Preview -->
                    @if(count($deletedImages) > 0)
                    <div class="mb-4 p-4 bg-red-50 rounded-lg">
                        <p class="text-sm text-red-800 mb-2">{{ count($deletedImages) }} images marked for deletion</p>
                        <button wire:click="confirmDeleteImages()" 
                                class="px-3 py-1.5 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">
                            Confirm Delete
                        </button>
                        <button wire:click="deletedImages = []" 
                                class="px-3 py-1.5 bg-gray-200 text-gray-700 text-sm rounded-lg ml-2">
                            Cancel
                        </button>
                    </div>
                    @endif
                    
                    <!-- Upload New Images -->
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center"
                         :class="{ 'border-blue-500 bg-blue-50': dropZoneActive }"
                         @dragover.prevent="dropZoneActive = true"
                         @dragleave="dropZoneActive = false"
                         @drop.prevent="dropZoneActive = false; $event.dataTransfer.files.length > 0 && $wire.uploadMultiple('newImages', $event.dataTransfer.files)">
                        
                        <span class="material-symbols-outlined text-4xl text-gray-400 mb-2">cloud_upload</span>
                        <p class="text-gray-600 mb-2">Drag and drop images here, or</p>
                        <label class="px-4 py-2 bg-royal-blue text-white rounded-lg cursor-pointer hover:bg-blue-800 transition-colors">
                            Browse Files
                            <input type="file" multiple accept="image/*" class="hidden" 
                                   wire:model="newImages"
                                   @change="$wire.newImages = $event.target.files">
                        </label>
                        
                        @if(count($newImages) > 0)
                        <p class="mt-2 text-sm text-green-600">{{ count($newImages) }} images selected</p>
                        @endif
                    </div>
                </div>

                <!-- Property Form Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" wire:model="property.title" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                        <input type="number" wire:model="property.price" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <input type="text" wire:model="property.address" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select wire:model="property.status" 
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="draft">Draft</option>
                            <option value="available">Available</option>
                            <option value="sold">Sold</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Property Type</label>
                        <select wire:model="property.property_type" 
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="apartment">Apartment</option>
                            <option value="villa">Villa</option>
                            <option value="office">Office</option>
                            <option value="commercial">Commercial</option>
                            <option value="plot">Plot</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 p-6 border-t border-gray-100 bg-gray-50">
                <button @click="$wire.showModal = false" 
                        class="px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-lg transition-colors">
                    Cancel
                </button>
                <button wire:click="save" 
                        class="px-4 py-2 bg-royal-blue text-white rounded-lg hover:bg-blue-800 transition-colors">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
```

---

## 3. Homepage Analysis & Recommendations

### Current State Analysis

The homepage at [`laravel/resources/views/welcome.blade.php`](laravel/resources/views/welcome.blade.php) serves as the public-facing entry point. Current structure:

#### Current Components
1. **Site Header**: Navigation and branding
2. **Hero Section**: Featured property carousel or search area
3. **Filters**: Search and filter sidebar
4. **Listings**: Grid of available properties (single section)

#### Identified Gaps

1. **No Sorting Options**
   - Users cannot sort by price, date, or popularity
   - Limited to whatever order the database returns

2. **No Pagination**
   - All properties load at once (see line 55 in [`Listings.php`](laravel/app/Livewire/Public/Listings.php))
   - `$query->get()` retrieves all matching records

3. **Single Listing Section**
   - All properties mixed together
   - No separation by property type (Villa, Apartment, Commercial, Plot)

4. **No View Toggle**
   - Only grid view available
   - No list view option for users who prefer compact display

### Why This Approach is Recommended

Organizing the homepage with sorting, pagination, and property type sections will:

1. **Enhance User Experience**: Users can quickly find properties matching their criteria
2. **Improve Performance**: Pagination reduces initial page load time
3. **Better Content Discovery**: Separate sections highlight different property types
4. **Increase Engagement**: View toggle and sorting options encourage exploration

### How to Implement with Code Examples

#### Enhanced Listings Component with Sorting, Pagination, and Sections

```php
<?php
// File: laravel/app/Livewire/Public/Listings.php

namespace App\Livewire\Public;

use App\Models\Property;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;

class Listings extends Component
{
    use WithPagination;

    public $properties = [];
    public $loading = true;
    public $error = null;
    public $settings = [];
    public $currentFilters = [];
    public $searchQuery = '';
    public $categoryFilter = 'all';
    
    // NEW: Sorting and View Options
    public $sortBy = 'newest'; // 'newest', 'price_low', 'price_high', 'popular'
    public $viewMode = 'grid'; // 'grid', 'list'

    protected $listeners = [
        'filtersApplied' => 'applyFilters',
        'filtersReset' => 'resetFilters',
        'searchUpdated' => 'applySearch',
        'categoryUpdated' => 'applyCategory'
    ];

    public function mount()
    {
        $this->loadSettings();
        $this->loadProperties();
    }

    public function loadSettings()
    {
        try {
            $this->settings = Setting::first()?->toArray() ?? [];
        } catch (\Exception $e) {
            $this->error = 'Failed to load settings';
        }
    }

    public function loadProperties()
    {
        try {
            $this->loading = true;
            $this->error = null;

            $query = Property::query();

            // Base filter: Only show available properties
            $query->where('status', 'available');

            // Apply additional filters
            $this->applyFiltersToQuery($query);

            // NEW: Apply sorting
            $this->applySorting($query);

            // NEW: Use pagination instead of get()
            $this->properties = $query->paginate(12);

        } catch (\Exception $e) {
            $this->error = 'Failed to load properties. Please try again.';
            $this->properties = collect();
        } finally {
            $this->loading = false;
        }
    }

    /**
     * Apply sorting to query
     */
    protected function applySorting($query)
    {
        switch ($this->sortBy) {
            case 'newest':
                $query->latest();
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
        }
    }

    public function applyFiltersToQuery($query)
    {
        // Apply search
        if (!empty($this->searchQuery)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('address', 'like', '%' . $this->searchQuery . '%');
            });
        }

        // Apply category filter
        if ($this->categoryFilter !== 'all') {
            switch ($this->categoryFilter) {
                case 'sale':
                    $query->where('status', 'available');
                    break;
                case 'rent':
                    // Assuming rental properties have a different status or type
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

        // Apply advanced filters
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
            $query->where('bathrooms', $this->currentFilters['bathrooms']);
        }

        return $query;
    }

    public function applyFilters($filters)
    {
        $this->currentFilters = $filters;
        $this->resetPage();
        $this->loadProperties();
    }

    public function resetFilters()
    {
        $this->currentFilters = [];
        $this->resetPage();
        $this->loadProperties();
    }

    public function applySearch($search)
    {
        $this->searchQuery = $search;
        $this->resetPage();
        $this->loadProperties();
    }

    public function applyCategory($category)
    {
        $this->categoryFilter = $category;
        $this->resetPage();
        $this->loadProperties();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
        $this->loadProperties();
    }

    public function updatedViewMode()
    {
        // Just for tracking, no action needed
    }

    /**
     * NEW: Get properties grouped by type for sectioned display
     */
    public function getPropertiesByType()
    {
        $properties = Property::where('status', 'available')
            ->when(!empty($this->currentFilters), function($query) {
                $this->applyFiltersToQuery($query);
            })
            ->when(!empty($this->searchQuery), function($query) {
                $query->where(function($q) {
                    $q->where('title', 'like', '%' . $this->searchQuery . '%')
                      ->orWhere('address', 'like', '%' . $this->searchQuery . '%');
                });
            })
            ->get();

        return [
            'villa' => $properties->where('property_type', 'villa')->take(6),
            'apartment' => $properties->where('property_type', 'apartment')->take(6),
            'commercial' => $properties->where('property_type', 'commercial')->take(6),
            'plot' => $properties->where('property_type', 'plot')->take(6),
        ];
    }

    public function getWhatsAppMessage($property)
    {
        $domain = request()->getHost();
        $agencyName = $this->settings['agency_name'] ?? 'Elite Homes';
        $message = "Hii i'm interested in\n*{$property['title']}*\nat {$property['address']}\nUID: {$property['id']}\nLink: {$domain}/property/{$property['id']}";
        $encodedMessage = urlencode($message);
        $phone = preg_replace('/[^\d]/', '', $this->settings['w_no'] ?? '');
        return "https://wa.me/{$phone}?text={$encodedMessage}";
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
        return view('livewire.public.listings');
    }
}
```

#### Enhanced Listings View with Sorting, Pagination, and View Toggle

```blade
{{-- File: laravel/resources/views/livewire/public/listings.blade.php --}}
<section class="px-4 sm:px-6 lg:px-10 py-6 sm:py-8 bg-gray-50/50 flex-1">
    <div class="max-w-[1280px] mx-auto">
        
        <!-- Sort and View Controls - NEW -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <h3 class="text-lg sm:text-xl font-bold text-[#121317]">{{ $title ?? 'Featured Properties' }}</h3>
            
            <div class="flex items-center gap-4">
                <!-- Sort Dropdown -->
                <div class="relative" x-data="{ sortOpen: false }">
                    <button @click="sortOpen = !sortOpen"
                            class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <span class="material-symbols-outlined text-[18px]">sort</span>
                        <span>
                            @switch($sortBy)
                                @case('newest') Newest First @break
                                @case('price_low') Price: Low to High @break
                                @case('price_high') Price: High to Low @break
                                @case('popular') Most Popular @break
                            @endswitch
                        </span>
                        <span class="material-symbols-outlined text-[16px]">expand_more</span>
                    </button>
                    
                    <div x-show="sortOpen" x-transition x-cloak
                         class="absolute right-0 top-full mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg z-20">
                        <ul class="py-2">
                            <li>
                                <button @click="$wire.sortBy = 'newest'; sortOpen = false"
                                        class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 {{ $sortBy === 'newest' ? 'text-royal-blue font-medium' : 'text-gray-700' }}">
                                    Newest First
                                </button>
                            </li>
                            <li>
                                <button @click="$wire.sortBy = 'price_low'; sortOpen = false"
                                        class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 {{ $sortBy === 'price_low' ? 'text-royal-blue font-medium' : 'text-gray-700' }}">
                                    Price: Low to High
                                </button>
                            </li>
                            <li>
                                <button @click="$wire.sortBy = 'price_high'; sortOpen = false"
                                        class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 {{ $sortBy === 'price_high' ? 'text-royal-blue font-medium' : 'text-gray-700' }}">
                                    Price: High to Low
                                </button>
                            </li>
                            <li>
                                <button @click="$wire.sortBy = 'popular'; sortOpen = false"
                                        class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 {{ $sortBy === 'popular' ? 'text-royal-blue font-medium' : 'text-gray-700' }}">
                                    Most Popular
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- View Toggle -->
                <div class="flex items-center bg-gray-100 rounded-lg p-1">
                    <button wire:click="$set('viewMode', 'grid')"
                            class="p-2 rounded transition-all {{ $viewMode === 'grid' ? 'bg-white shadow-sm text-royal-blue' : 'text-gray-400 hover:text-gray-600' }}"
                            title="Grid View">
                        <span class="material-symbols-outlined text-[20px]">grid_view</span>
                    </button>
                    <button wire:click="$set('viewMode', 'list')"
                            class="p-2 rounded transition-all {{ $viewMode === 'list' ? 'bg-white shadow-sm text-royal-blue' : 'text-gray-400 hover:text-gray-600' }}"
                            title="List View">
                        <span class="material-symbols-outlined text-[20px]">view_list</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        @if($loading)
            <div class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                <span class="ml-2 text-gray-600">Loading properties...</span>
            </div>
        @endif

        <!-- Error State -->
        @if($error && !$loading)
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <span class="material-symbols-outlined text-red-400 text-xl mr-2">error</span>
                    <div>
                        <h4 class="text-red-800 font-medium">Error Loading Properties</h4>
                        <p class="text-red-600 text-sm">{{ $error }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Properties Grid/List -->
        @if(!$loading && !$error)
            @if($viewMode === 'grid')
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                @forelse($properties as $property)
                    @include('livewire.public.partials.property-card', ['property' => $property])
                @empty
                    <div class="col-span-full text-center py-12">
                        <span class="material-symbols-outlined text-gray-400 text-6xl mb-4 block">home_work</span>
                        <h4 class="text-gray-600 font-medium text-lg">No Properties Available</h4>
                        <p class="text-gray-500 text-sm mt-1">Check back later for new listings.</p>
                    </div>
                @endforelse
            </div>
            @else
            <!-- List View -->
            <div class="flex flex-col gap-4">
                @forelse($properties as $property)
                    @include('livewire.public.partials.property-list-item', ['property' => $property])
                @empty
                    <div class="text-center py-12">
                        <span class="material-symbols-outlined text-gray-400 text-6xl mb-4 block">home_work</span>
                        <h4 class="text-gray-600 font-medium text-lg">No Properties Available</h4>
                        <p class="text-gray-500 text-sm mt-1">Check back later for new listings.</p>
                    </div>
                @endforelse
            </div>
            @endif

            <!-- Pagination - NEW -->
            @if($properties->hasPages())
            <div class="flex justify-center mt-8">
                <nav class="flex items-center gap-2">
                    {{ $properties->links() }}
                </nav>
            </div>
            @endif
        @endif
    </div>
</section>
```

#### Sectioned Homepage Layout (Alternative Approach)

For a homepage with separate sections for each property type:

```blade
{{-- File: laravel/resources/views/livewire/public/listings.blade.php --}}
<section class="px-4 sm:px-6 lg:px-10 py-6 sm:py-8 bg-gray-50/50 flex-1">
    <div class="max-w-[1280px] mx-auto">
        
        <!-- Global Controls -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <h3 class="text-lg sm:text-xl font-bold text-[#121317]">{{ $title ?? 'Featured Properties' }}</h3>
            <div class="flex items-center gap-4">
                <!-- Search -->
                <div class="relative">
                    <input type="text" 
                           wire:model.live.debounce.300ms="searchQuery"
                           placeholder="Search properties..."
                           class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                    </span>
                </div>
                
                <!-- View Toggle -->
                <div class="flex items-center bg-gray-100 rounded-lg p-1">
                    <button wire:click="$set('viewMode', 'grid')"
                            class="p-2 rounded {{ $viewMode === 'grid' ? 'bg-white shadow-sm' : '' }}">
                        <span class="material-symbols-outlined">grid_view</span>
                    </button>
                    <button wire:click="$set('viewMode', 'list')"
                            class="p-2 rounded {{ $viewMode === 'list' ? 'bg-white shadow-sm' : '' }}">
                        <span class="material-symbols-outlined">view_list</span>
                    </button>
                </div>
            </div>
        </div>

        @php $propertiesByType = $this->getPropertiesByType(); @endphp

        <!-- Villa Listings Section -->
        @if($propertiesByType['villa']->count() > 0)
        <div class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-500">house</span>
                    Villa Listings
                </h2>
                <a href="{{ url('/properties?villa=true') }}" class="text-royal-blue hover:underline text-sm">
                    View All Villas →
                </a>
            </div>
            @include('livewire.public.partials.property-section-grid', ['properties' => $propertiesByType['villa']])
        </div>
        @endif

        <!-- Apartment Listings Section -->
        @if($propertiesByType['apartment']->count() > 0)
        <div class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-500">apartment</span>
                    Apartment Listings
                </h2>
                <a href="{{ url('/properties?apartment=true') }}" class="text-royal-blue hover:underline text-sm">
                    View All Apartments →
                </a>
            </div>
            @include('livewire.public.partials.property-section-grid', ['properties' => $propertiesByType['apartment']])
        </div>
        @endif

        <!-- Commercial Listings Section -->
        @if($propertiesByType['commercial']->count() > 0)
        <div class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-cyan-500">business</span>
                    Commercial Listings
                </h2>
                <a href="{{ url('/properties?commercial=true') }}" class="text-royal-blue hover:underline text-sm">
                    View All Commercial →
                </a>
            </div>
            @include('livewire.public.partials.property-section-grid', ['properties' => $propertiesByType['commercial']])
        </div>
        @endif

        <!-- Plot Listings Section -->
        @if($propertiesByType['plot']->count() > 0)
        <div class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-500">landscape</span>
                    Plot Listings
                </h2>
                <a href="{{ url('/properties?plot=true') }}" class="text-royal-blue hover:underline text-sm">
                    View All Plots →
                </a>
            </div>
            @include('livewire.public.partials.property-section-grid', ['properties' => $propertiesByType['plot']])
        </div>
        @endif

        <!-- No Properties State -->
        @if($propertiesByType['villa']->isEmpty() && 
            $propertiesByType['apartment']->isEmpty() && 
            $propertiesByType['commercial']->isEmpty() && 
            $propertiesByType['plot']->isEmpty())
            <div class="text-center py-12">
                <span class="material-symbols-outlined text-gray-400 text-6xl mb-4 block">home_work</span>
                <h4 class="text-gray-600 font-medium text-lg">No Properties Available</h4>
                <p class="text-gray-500 text-sm mt-1">Check back later for new listings.</p>
            </div>
        @endif
    </div>
</section>
```

#### Property Card Component (Reusable)

```blade
{{-- File: laravel/resources/views/livewire/public/partials/property-card.blade.php --}}
<article class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
    <div class="relative aspect-[4/3] overflow-hidden">
        <!-- Property Badge -->
        @php $badge = $this->getPropertyBadge($property) @endphp
        @if($badge)
            <div class="absolute top-3 left-3 z-10 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm"
                 style="background-color: {{ $badge['color'] }}">
                {{ $badge['label'] }}
            </div>
        @endif

        <!-- Favorite Button -->
        <div class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <button class="bg-white/90 backdrop-blur-md p-2 rounded-full text-gray-700 hover:text-red-500 hover:bg-white transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[20px] block">favorite</span>
            </button>
        </div>

        <!-- Property Image -->
        <img alt="{{ $property['title'] }} image"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
             src="{{ asset('storage/' . $property['primary_image_path']) ?? asset('images/placeholder.jpg') }}">
        <div class="absolute bottom-0 left-0 w-full h-1/3 bg-gradient-to-t from-black/50 to-transparent pointer-events-none"></div>
    </div>

    <div class="p-5 flex flex-col gap-3 flex-1">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="text-gold font-bold text-lg sm:text-xl tracking-tight">
                    {{ $property['price'] ? '₹' . number_format($property['price']) : 'Price TBD' }}
                </h3>
                <h4 class="text-[#121317] font-bold text-base sm:text-lg leading-tight mt-1 group-hover:text-primary transition-colors">
                    {{ $property['title'] }}
                </h4>
            </div>
        </div>

        <div class="flex items-center gap-1 text-[#666e85] text-sm">
            <span class="material-symbols-outlined text-[18px]">location_on</span>
            <p class="truncate">{{ $property['address'] ?? 'Location TBD' }}</p>
        </div>

        <!-- Property Specs -->
        <div class="flex items-center gap-4 py-3 border-t border-b border-gray-50 my-2 mt-auto">
            @if($property['bedrooms'] && $property['bedrooms'] > 0)
                <div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                    <span class="material-symbols-outlined text-gray-400 text-[20px]">bed</span>
                    <span>{{ $property['bedrooms'] }} Beds</span>
                </div>
            @endif
            @if($property['bathrooms'] && $property['bathrooms'] > 0)
                <div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                    <span class="material-symbols-outlined text-gray-400 text-[20px]">bathtub</span>
                    <span>{{ $property['bathrooms'] }} Baths</span>
                </div>
            @endif
            @if($property['area_sqft'] && $property['area_sqft'] !== 'N/A')
                <div class="flex items-center gap-1.5 text-sm font-medium text-gray-700">
                    <span class="material-symbols-outlined text-gray-400 text-[20px]">square_foot</span>
                    <span>{{ number_format($property['area_sqft']) }} sqft</span>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 mt-1">
            <a href="{{ url('/property/' . $property['id']) }}" 
               class="flex-1 h-10 rounded-full border border-primary text-primary font-bold text-sm hover:bg-primary/5 transition-colors inline-flex items-center justify-center">
                View Details
            </a>
            <a href="{{ $this->getWhatsAppMessage($property) }}" 
               target="_blank" rel="noopener noreferrer"
               class="flex-1 h-10 rounded-full bg-whatsapp text-white font-bold text-sm flex items-center justify-center gap-2 hover:brightness-105 transition-all">
                <i class="fa-brands fa-whatsapp text-[16px]"></i>
                WhatsApp
            </a>
        </div>
    </div>
</article>
```

### Additional Homepage Improvements

1. **Quick Filters Bar**
   - Add quick filter chips for common searches (New, Popular, Under 50L, etc.)

2. **Saved Searches**
   - Allow users to save filter combinations for future use

3. **Property Comparison**
   - Add compare functionality to select up to 4 properties

4. **Map View Integration**
   - Show properties on an interactive map

5. **Mortgage Calculator Widget**
   - Add a simple EMI calculator for properties

---

## 4. Implementation Priority Matrix

| Feature | Complexity | Impact | Priority | Sprint |
|---------|------------|--------|----------|--------|
| Dashboard real data | Medium | High | P0 | 1 |
| Inventory sorting | Low | High | P0 | 1 |
| Homepage pagination | Medium | High | P0 | 1 |
| Homepage sorting | Low | Medium | P1 | 2 |
| View toggle (grid/list) | Low | Medium | P1 | 2 |
| Edit modal image reorder | Medium | High | P1 | 2 |
| Property type sections | Medium | Medium | P2 | 3 |
| Bulk actions | Low | Medium | P2 | 3 |
| Quick filters | Low | Low | P3 | 4 |
| Property comparison | High | Low | P3 | 4 |

---

## 5. Conclusion

This analysis report provides comprehensive recommendations for improving three critical pages of the BrokerBase platform:

1. **Dashboard**: Transform from static placeholder to dynamic data display with real-time statistics
2. **Inventory**: Enhance with sorting, bulk actions, and improved image management
3. **Homepage**: Organize by property type with sorting, pagination, and view options

The recommended implementations follow Laravel and Livewire best practices, maintain consistency with the existing codebase, and focus on user experience improvements that will drive business value.

### Next Steps

1. **Sprint 1**: Implement dashboard real data and homepage pagination
2. **Sprint 2**: Add sorting/view options and enhanced edit modal
3. **Sprint 3**: Property type sections and bulk actions
4. **Sprint 4**: Additional UX improvements (quick filters, comparison)

---

**Document prepared for BrokerBase Development Team**  
*Questions and feedback welcome*
