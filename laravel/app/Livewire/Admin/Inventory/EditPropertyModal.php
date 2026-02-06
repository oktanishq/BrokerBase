<?php

namespace App\Livewire\Admin\Inventory;

use App\Models\Property;
use App\Data\AmenitiesData;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditPropertyModal extends Component
{
    use WithFileUploads;

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

    // Image management
    public $newImages = [];         // Newly uploaded images (Using WithFileUploads trait, do NOT cast)
    public $existingImages = [];     // Images from database
    public $deletedImagePaths = [];  // Paths of images marked for deletion

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

        // Load existing images from database
        $this->existingImages = [];
        $this->newImages = [];
        $this->deletedImagePaths = [];

        if (isset($this->property['images'])) {
            $imagesData = $this->property['images'];
            
            // If images is a string, decode it (handle previously corrupted double-encoded data)
            if (is_string($imagesData)) {
                $decoded = json_decode($imagesData, true);
                // If still a string after decode, it's double-encoded - decode again
                if (is_string($decoded)) {
                    $decoded = json_decode($decoded, true);
                }
                $imagesData = $decoded;
            }
            
            // Ensure we have an array
            if (is_array($imagesData)) {
                // Normalize existing images - ensure proper URL format
                $this->existingImages = array_map(function($img) {
                    if (!is_array($img)) {
                        return null;
                    }
                    
                    // Ensure URL is properly generated using Storage::url
                    $path = $img['path'] ?? '';
                    if ($path && !isset($img['url'])) {
                        $img['url'] = Storage::url($path);
                    }
                    
                    return $img;
                }, $imagesData);
                
                // Filter out any null values
                $this->existingImages = array_filter($this->existingImages);
                $this->existingImages = array_values($this->existingImages);
            }
            
            // SAFEGUARD: Ensure existingImages is always an array
            if (!is_array($this->existingImages)) {
                $this->existingImages = [];
            }
        }

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

        // Reset image management
        $this->newImages = [];
        $this->existingImages = [];
        $this->deletedImagePaths = [];

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

    // ==================== Image Management Methods ====================

    /**
     * Get all images combined (existing + new)
     */
    public function getAllImagesProperty(): array
    {
        $allImages = [];
        
        // Add existing images that are not deleted
        foreach ($this->existingImages as $index => $image) {
            if (!in_array($image['path'] ?? '', $this->deletedImagePaths)) {
                $image['order'] = $index + 1;
                $image['is_primary'] = $index === 0;
                $allImages[] = $image;
            }
        }
        
        // Add new images
        foreach ($this->newImages as $index => $image) {
            $allImages[] = [
                'path' => $image['path'] ?? '',
                'url' => $image['url'] ?? $image->temporaryUrl(),
                'order' => count($allImages) + 1,
                'is_primary' => count($allImages) === 0,
                'original_name' => $image->getClientOriginalName() ?? 'image.jpg',
                'size' => $image->getSize(),
                'mime_type' => $image->getMimeType(),
                'is_watermarked' => false,
            ];
        }
        
        return $allImages;
    }

    /**
     * Remove a newly uploaded image
     */
    public function removeNewImage($index)
    {
        unset($this->newImages[$index]);
        $this->newImages = array_values($this->newImages);
    }

    /**
     * Mark an existing image for deletion
     */
    public function deleteExistingImage($index)
    {
        if (isset($this->existingImages[$index])) {
            $imagePath = $this->existingImages[$index]['path'] ?? '';
            if ($imagePath && !in_array($imagePath, $this->deletedImagePaths)) {
                $this->deletedImagePaths[] = $imagePath;
            }
            unset($this->existingImages[$index]);
            $this->existingImages = array_values($this->existingImages);
        }
    }

    /**
     * Reorder images after drag and drop
     */
    public function reorderImages($newOrder)
    {
        // $newOrder contains the new order of indices for existingImages
        // But since we have two arrays (existing + new), we need to handle this differently
        // For simplicity, we'll combine and reorder
        
        $allImages = $this->getAllImagesProperty();
        $reorderedImages = [];
        
        foreach ($newOrder as $index) {
            if (isset($allImages[$index])) {
                $reorderedImages[] = $allImages[$index];
            }
        }
        
        // Separate back into existing and new
        $this->existingImages = [];
        $this->newImages = [];
        
        foreach ($reorderedImages as $image) {
            if (isset($image['path']) && strpos($image['path'], 'temporary') === false) {
                // Existing image
                $this->existingImages[] = $image;
            } else {
                // New image - skip for now as we can't persist temporary uploads
                // In production, you would upload these first
            }
        }
    }

    /**
     * Upload new images
     */
    public function updatedNewImages($images)
    {
        if (!is_array($images)) {
            $images = [$images];
        }
        
        foreach ($images as $image) {
            if ($image instanceof \Illuminate\Http\UploadedFile) {
                // Validate image
                $imageUploadService = new ImageUploadService();
                $validation = $imageUploadService->validateImage($image);
                
                if (!$validation['valid']) {
                    session()->flash('error', implode(', ', $validation['errors']));
                    continue;
                }
                
                $this->newImages[] = $image;
            }
        }
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
            'price' => 'nullable|numeric|min:0',
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
            'price.required' => 'Please enter the property price (or leave empty for TBD).',
            'price.numeric' => 'The price must be a valid number.',
            'price.min' => 'The price must be a positive number or leave empty for TBD.',
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
            
            $userId = auth()->id() ?? $property->user_id ?? 1;
            $propertyId = $property->id;
            
            // Upload new images
            $imageUploadService = new ImageUploadService();
            $allImages = $this->existingImages;
            
            // SAFEGUARD: Ensure allImages is always an array (handle Livewire serialization)
            if (is_string($allImages)) {
                $decoded = json_decode($allImages, true);
                if (is_string($decoded)) {
                    $decoded = json_decode($decoded, true);
                }
                $allImages = $decoded ?? [];
            }
            if (!is_array($allImages)) {
                $allImages = [];
            }
            
            // Remove deleted images from the list
            $allImages = array_values(array_filter($allImages, function($img) {
                return !in_array($img['path'] ?? '', $this->deletedImagePaths);
            }));
            
            // Process new images
            foreach ($this->newImages as $index => $image) {
                if ($image instanceof \Illuminate\Http\UploadedFile) {
                    $order = count($allImages) + $index + 1;
                    $uploadedImage = $imageUploadService->uploadPropertyImageForEdit(
                        $image, 
                        $userId, 
                        $propertyId, 
                        $order
                    );
                    
                    if ($uploadedImage) {
                        $allImages[] = $uploadedImage;
                    }
                }
            }
            
            // Normalize URLs for all images (ensure consistent URL format)
            foreach ($allImages as &$img) {
                if (isset($img['path']) && !isset($img['url'])) {
                    $img['url'] = Storage::url($img['path']);
                }
                // Fix any malformed URLs
                if (isset($img['url']) && strpos($img['url'], '127.0.0.1:8000') !== false) {
                    $img['url'] = str_replace('127.0.0.1:8000', 'localhost', $img['url']);
                }
            }
            unset($img);
            
            // Apply watermark if enabled
            if ($this->watermark_enabled) {
                foreach ($allImages as &$img) {
                    if (!($img['is_watermarked'] ?? false)) {
                        $imageUploadService->applyWatermark($img['path']);
                        $img['is_watermarked'] = true;
                    }
                }
                unset($img);
            }
            
            // Update order and is_primary for all images
            $finalImages = [];
            foreach ($allImages as $index => $image) {
                $image['order'] = $index + 1;
                $image['is_primary'] = $index === 0;
                $finalImages[] = $image;
            }
            
            // Delete marked images from storage
            if (!empty($this->deletedImagePaths)) {
                $imageUploadService->deletePropertyImages($this->deletedImagePaths);
            }

            // Update the property with all data using fill() + save()
            // This avoids the double-encoding issue with update() and casts
            $property->fill([
                'title' => $this->title,
                'description' => $this->description,
                'property_type' => $this->property_type,
                'price' => $this->price ?: null,
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
            ]);
            
            // Set images as array - the cast will encode it ONCE during save()
            $property->images = $finalImages;
            $property->save();

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