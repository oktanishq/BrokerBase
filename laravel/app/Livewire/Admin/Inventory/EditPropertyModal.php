<?php

namespace App\Livewire\Admin\Inventory;

use App\Models\Property;
use App\Data\AmenitiesData;
use Livewire\Component;

class EditPropertyModal extends Component
{
    public $isOpen = false;
    public $property = null;
    public $saving = false;
    public $currentTab = 'overview';

    // Form data
    public $title = '';
    public $description = '';
    public $property_type = 'apartment';
    public $price = '';
    public $area_sqft = '';
    public $address = '';
    public $latitude = '';
    public $longitude = '';
    public $maps_embed_url = '';
    public $bedrooms = '';
    public $bathrooms = '';
    public $amenities = [];
    public $watermark_enabled = true;
    public $status = 'available';
    public $is_featured = false;
    public $label_type = 'none';
    public $custom_label_color = '#3B82F6';
    public $owner_name = '';
    public $owner_phone = '';
    public $net_price = '';
    public $private_notes = '';

    // Advanced amenities system
    public $amenitiesSearch = '';
    public $showAmenitiesDropdown = false;
    public $availableAmenities = [];

    protected $listeners = [
        'open-edit-modal' => 'openModal',
        'close-edit-modal' => 'closeModal',
    ];

    public function openModal($propertyData)
    {
        $this->property = $propertyData;
        $this->populateFormData();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    public function populateFormData()
    {
        if (!$this->property) return;

        $this->title = $this->property['title'] ?? '';
        $this->description = $this->property['description'] ?? '';
        $this->property_type = $this->property['property_type'] ?? 'apartment';
        $this->price = $this->property['price'] ? number_format((float)$this->property['price']) : '';
        $this->area_sqft = $this->property['area_sqft'] ?? '';
        $this->address = $this->property['address'] ?? '';
        $this->latitude = $this->property['latitude'] ?? '';
        $this->longitude = $this->property['longitude'] ?? '';
        $this->maps_embed_url = $this->property['maps_embed_url'] ?? '';
        $this->bedrooms = $this->property['bedrooms'] ?? '';
        $this->bathrooms = $this->property['bathrooms'] ?? '';
        $this->amenities = $this->property['amenities'] ?? [];
        $this->watermark_enabled = $this->property['watermark_enabled'] ?? true;
        $this->status = $this->property['status'] ?? 'available';
        $this->is_featured = $this->property['is_featured'] ?? false;
        $this->label_type = $this->property['label'] ?? 'none';
        $this->custom_label_color = $this->property['custom_label_color'] ?? '#3B82F6';
        $this->owner_name = $this->property['owner_name'] ?? '';
        $this->owner_phone = $this->property['owner_phone'] ?? '';
        $this->net_price = $this->property['net_price'] ? number_format((float)$this->property['net_price']) : '';
        $this->private_notes = $this->property['private_notes'] ?? '';

        // Initialize amenities data
        $this->availableAmenities = AmenitiesData::getAll();
    }

    public function resetForm()
    {
        $this->property = null;
        $this->title = '';
        $this->description = '';
        $this->property_type = 'apartment';
        $this->price = '';
        $this->area_sqft = '';
        $this->address = '';
        $this->latitude = '';
        $this->longitude = '';
        $this->maps_embed_url = '';
        $this->bedrooms = '';
        $this->bathrooms = '';
        $this->amenities = [];
        $this->watermark_enabled = true;
        $this->status = 'available';
        $this->is_featured = false;
        $this->label_type = 'none';
        $this->custom_label_color = '#3B82F6';
        $this->owner_name = '';
        $this->owner_phone = '';
        $this->net_price = '';
        $this->private_notes = '';
        $this->currentTab = 'overview';

        // Reset amenities system
        $this->amenitiesSearch = '';
        $this->showAmenitiesDropdown = false;
        $this->availableAmenities = AmenitiesData::getAll();
    }

    public function setTab($tab)
    {
        $this->currentTab = $tab;
    }

    public function addAmenity($amenityName = null)
    {
        // If specific amenity name provided (from dropdown), add it
        if ($amenityName) {
            if (!in_array($amenityName, $this->amenities)) {
                $this->amenities[] = $amenityName;
            }
        }
        // If no amenity name but search has text, add it as custom amenity
        elseif (!empty(trim($this->amenitiesSearch))) {
            $customAmenity = trim($this->amenitiesSearch);
            if (!in_array($customAmenity, $this->amenities)) {
                $this->amenities[] = $customAmenity;
            }
        }
        // Legacy: add empty for manual input (shouldn't happen with new system)
        else {
            $this->amenities[] = '';
        }

        // Reset search and close dropdown
        $this->amenitiesSearch = '';
        $this->showAmenitiesDropdown = false;
    }

    public function removeAmenity($identifier)
    {
        if (is_numeric($identifier)) {
            // Old system: remove by index
            unset($this->amenities[$identifier]);
            $this->amenities = array_values($this->amenities);
        } else {
            // New system: remove by amenity name
            $this->amenities = array_filter($this->amenities, function ($amenity) use ($identifier) {
                return $amenity !== $identifier;
            });
        }
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setLabelType($type)
    {
        $this->label_type = $type;
    }

    public function loadMap()
    {
        // Check if coordinates are provided
        if (!$this->latitude || !$this->longitude) {
            session()->flash('error', 'Please enter latitude and longitude to load the map');
            return;
        }

        // Validate coordinates
        $lat = (float)$this->latitude;
        $lng = (float)$this->longitude;

        if ($lat < -90 || $lat > 90) {
            session()->flash('error', 'Latitude must be between -90 and 90');
            return;
        }

        if ($lng < -180 || $lng > 180) {
            session()->flash('error', 'Longitude must be between -180 and 180');
            return;
        }

        // Generate Google Maps embed URL
        $this->maps_embed_url = "https://maps.google.com/maps?q={$lat},{$lng}&z=15&output=embed&t=&z=15&ie=UTF8&iwloc=&output=embed&maptype=satellite&source=embed&disableDefaultUI=true&zoomControl=false&mapTypeControl=false&streetViewControl=false&rotateControl=false&fullscreenControl=false&scrollwheel=false";

        // Flash success message
        session()->flash('success', 'Map loaded successfully!');
    }

    // Advanced amenities methods
    public function updatedAmenitiesSearch()
    {
        $this->showAmenitiesDropdown = !empty($this->amenitiesSearch);
    }

    public function saveChanges()
    {
        if (!$this->property || !isset($this->property['id'])) return;

        $this->saving = true;

        // Clean price fields before validation
        $this->price = str_replace(',', '', $this->price);
        $this->net_price = str_replace(',', '', $this->net_price);

        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'required|in:apartment,villa,plot,commercial,office',
            'price' => 'required|numeric|min:0',
            'area_sqft' => 'nullable|numeric|min:0',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'maps_embed_url' => 'nullable|url',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:100',
            'owner_name' => 'nullable|string|max:255',
            'owner_phone' => 'nullable|string|max:20',
            'net_price' => 'nullable|numeric|min:0',
            'private_notes' => 'nullable|string',
        ], [
            'title.required' => 'Please provide a title for the property.',
            'title.max' => 'The property title must not exceed 255 characters.',
            'property_type.required' => 'Please select a property type.',
            'property_type.in' => 'Please select a valid property type.',
            'price.required' => 'Please enter the property price.',
            'price.numeric' => 'The price must be a valid number.',
            'price.min' => 'The price must be a positive number.',
            'area_sqft.numeric' => 'The area must be a valid number.',
            'area_sqft.min' => 'The area must be a positive number.',
            'address.max' => 'The address must not exceed 500 characters.',
            'latitude.numeric' => 'Latitude must be a valid number.',
            'latitude.between' => 'Latitude must be between -90 and 90.',
            'longitude.numeric' => 'Longitude must be a valid number.',
            'longitude.between' => 'Longitude must be between -180 and 180.',
            'maps_embed_url.url' => 'Please provide a valid URL for the maps embed.',
            'bedrooms.integer' => 'Bedrooms must be a whole number.',
            'bedrooms.min' => 'Bedrooms cannot be negative.',
            'bathrooms.integer' => 'Bathrooms must be a whole number.',
            'bathrooms.min' => 'Bathrooms cannot be negative.',
            'amenities.array' => 'Amenities must be a list.',
            'amenities.*.string' => 'Each amenity must be text.',
            'amenities.*.max' => 'Each amenity must not exceed 100 characters.',
            'owner_name.max' => 'Owner name must not exceed 255 characters.',
            'owner_phone.max' => 'Owner phone must not exceed 20 characters.',
            'net_price.numeric' => 'Net price must be a valid number.',
            'net_price.min' => 'Net price must be a positive number.',
        ]);

        try {
            $property = Property::findOrFail($this->property['id']);

            $updateData = [
                'title' => $this->title,
                'description' => $this->description,
                'property_type' => $this->property_type,
                'price' => $this->price,
                'area_sqft' => $this->area_sqft ?: null,
                'address' => $this->address ?: null,
                'latitude' => $this->latitude ?: null,
                'longitude' => $this->longitude ?: null,
                'maps_embed_url' => $this->maps_embed_url ?: null,
                'bedrooms' => $this->bedrooms ?: null,
                'bathrooms' => $this->bathrooms ?: null,
                'amenities' => $this->amenities,
                'watermark_enabled' => $this->watermark_enabled,
                'status' => $this->status,
                'is_featured' => $this->is_featured,
                'label_type' => $this->label_type,
                'custom_label_color' => $this->custom_label_color,
                'owner_name' => $this->owner_name ?: null,
                'owner_phone' => $this->owner_phone ?: null,
                'net_price' => $this->net_price ?: null,
                'private_notes' => $this->private_notes ?: null,
            ];

            $property->update($updateData);

            // Emit event to refresh inventory with updated data
            $this->dispatch('property-updated', propertyId: $this->property['id'], data: $property->toArray());

            // Close modal
            $this->closeModal();

            // Show success message
            session()->flash('success', 'Property updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors specifically
            session()->flash('error', 'Validation failed: ' . implode(', ', $e->errors()));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle property not found
            session()->flash('error', 'Property not found.');
        } catch (\Exception $error) {
            // Handle other errors
            session()->flash('error', 'Failed to update property: ' . $error->getMessage());
        } finally {
            $this->saving = false;
        }
    }

    public function render()
    {
        return view('livewire.admin.inventory.edit-property-modal');
    }
}