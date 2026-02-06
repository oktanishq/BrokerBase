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

    // Image management - Single combined array approach
    // All images (existing + new) are stored in a unified format for seamless reordering
    public $allImages = [];           // Combined array of all images
    public $newImageFiles = [];        // Temporary files uploaded via wire:model
    public $deletedImagePaths = [];    // Paths of existing images marked for deletion

    // Advanced amenities system
    public $amenitiesSearch = '';
    public $showAmenitiesDropdown = false;
    public $availableAmenities = [];

    /**
     * Normalize amenities array to ensure it's a clean indexed array
     * Prevents corruption from unset() operations
     */
    protected function normalizeAmenities(): void
    {
        if (!is_array($this->amenities)) {
            $this->amenities = [];
            return;
        }
        
        // Filter out empty/null values and reindex
        $this->amenities = array_values(array_filter($this->amenities, function ($amenity) {
            return $amenity !== null && $amenity !== '' && trim($amenity) !== '';
        }));
    }

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
        
        // Load and normalize amenities data
        $this->amenities = $this->property['amenities'] ?? [];
        $this->normalizeAmenities();
        $this->watermark_enabled = $this->property['watermark_enabled'] ?? true;
        $this->status = $this->property['status'] ?? 'available';
        $this->is_featured = $this->property['is_featured'] ?? false;
        $this->label_type = $this->property['label'] ?? 'none';
        $this->custom_label_color = $this->property['custom_label_color'] ?? '#3B82F6';
        $this->owner_name = $this->property['owner_name'] ?? '';
        $this->owner_phone = $this->property['owner_phone'] ?? '';
        $this->net_price = $this->property['net_price'] ? number_format((float)$this->property['net_price']) : '';
        $this->private_notes = $this->property['private_notes'] ?? '';

        // Initialize image management
        $this->allImages = [];
        $this->newImageFiles = [];
        $this->deletedImagePaths = [];

        // Load existing images from database into allImages array
        if (isset($this->property['images'])) {
            $imagesData = $this->property['images'];
            
            // Handle double-encoded JSON
            if (is_string($imagesData)) {
                $decoded = json_decode($imagesData, true);
                if (is_string($decoded)) {
                    $decoded = json_decode($decoded, true);
                }
                $imagesData = $decoded;
            }
            
            if (is_array($imagesData)) {
                // Add existing images to allImages array with 'existing' type
                foreach ($imagesData as $img) {
                    if (!is_array($img)) continue;
                    
                    // Ensure URL is properly generated
                    $path = $img['path'] ?? '';
                    if ($path && !isset($img['url'])) {
                        $img['url'] = Storage::url($path);
                    }
                    
                    $this->allImages[] = [
                        'type' => 'existing',
                        'data' => $img
                    ];
                }
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
        $this->normalizeAmenities();
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
        $this->allImages = [];
        $this->newImageFiles = [];
        $this->deletedImagePaths = [];

        // Reset amenities system
        $this->amenitiesSearch = '';
        $this->showAmenitiesDropdown = false;
        $this->availableAmenities = AmenitiesData::getAll();
    }

    /**
     * Normalize amenities for database storage
     * Ensures clean indexed array is saved
     */
    protected function normalizeAmenitiesForSave(array $amenities): array
    {
        // Filter out empty/null values and reindex
        $normalized = array_values(array_filter($amenities, function ($amenity) {
            return $amenity !== null && $amenity !== '' && trim($amenity) !== '';
        }));
        
        // Ensure all values are strings
        return array_map(function ($amenity) {
            return (string) $amenity;
        }, $normalized);
    }

    public function setTab($tab)
    {
        $this->currentTab = $tab;
    }

    public function addAmenity($amenityName = null)
    {
        if ($amenityName) {
            if (!in_array($amenityName, $this->amenities)) {
                $this->amenities[] = $amenityName;
            }
        } elseif (!empty(trim($this->amenitiesSearch))) {
            $customAmenity = trim($this->amenitiesSearch);
            if (!in_array($customAmenity, $this->amenities)) {
                $this->amenities[] = $customAmenity;
            }
        } else {
            $this->amenities[] = '';
        }

        $this->amenitiesSearch = '';
        $this->showAmenitiesDropdown = false;
    }

    public function removeAmenity($identifier)
    {
        if (is_numeric($identifier)) {
            unset($this->amenities[$identifier]);
            $this->amenities = array_values($this->amenities);
        } else {
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
        if (!$this->latitude || !$this->longitude) {
            session()->flash('error', 'Please enter latitude and longitude to load the map');
            return;
        }

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

        $this->maps_embed_url = "https://maps.google.com/maps?q={$lat},{$lng}&z=15&output=embed&t=&z=15&ie=UTF8&iwloc=&output=embed&maptype=satellite&source=embed&disableDefaultUI=true&zoomControl=false&mapTypeControl=false&streetViewControl=false&rotateControl=false&fullscreenControl=false&scrollwheel=false";

        session()->flash('success', 'Map loaded successfully!');
    }

    public function updatedAmenitiesSearch()
    {
        $this->showAmenitiesDropdown = !empty($this->amenitiesSearch);
    }

    // ==================== Image Management Methods ====================

    /**
     * Handle newly uploaded files - convert to unified format immediately
     * This allows seamless reordering with existing images
     */
    public function updatedNewImageFiles($files)
    {
        if (!is_array($files)) {
            $files = [$files];
        }

        $imageUploadService = new ImageUploadService();

        foreach ($files as $file) {
            if ($file instanceof \Illuminate\Http\UploadedFile) {
                // Validate image
                $validation = $imageUploadService->validateImage($file);
                
                if (!$validation['valid']) {
                    session()->flash('error', 'Invalid image: ' . implode(', ', $validation['errors']));
                    // Remove invalid file
                    foreach ($this->newImageFiles as $key => $existingFile) {
                        if ($existingFile instanceof \Illuminate\Http\UploadedFile && 
                            $existingFile->getClientOriginalName() === $file->getClientOriginalName() &&
                            $existingFile->getSize() === $file->getSize()) {
                            unset($this->newImageFiles[$key]);
                            $this->newImageFiles = array_values($this->newImageFiles);
                            break;
                        }
                    }
                    continue;
                }

                // Add to allImages array as a new image
                $this->allImages[] = [
                    'type' => 'new',
                    'data' => [
                        'temporary_file' => $file,
                        'temporary_url' => $file->temporaryUrl(),
                        'original_name' => $file->getClientOriginalName() ?? 'image.jpg',
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                    ]
                ];
            }
        }

        // Clear the file input after processing
        $this->newImageFiles = [];
    }

    /**
     * Remove an image by index
     */
    public function removeImage($index)
    {
        if (isset($this->allImages[$index])) {
            $imageItem = $this->allImages[$index];
            
            // If it's an existing image, mark for deletion
            if ($imageItem['type'] === 'existing') {
                $path = $imageItem['data']['path'] ?? '';
                if ($path && !in_array($path, $this->deletedImagePaths)) {
                    $this->deletedImagePaths[] = $path;
                }
            }
            
            // Remove from array
            unset($this->allImages[$index]);
            $this->allImages = array_values($this->allImages);
        }
    }

    /**
     * Reorder images after drag and drop
     * $newOrder contains the new positions (0, 1, 2...)
     */
    public function reorderImages($newOrder)
    {
        $reorderedImages = [];
        
        foreach ($newOrder as $newIndex) {
            if (isset($this->allImages[$newIndex])) {
                $reorderedImages[] = $this->allImages[$newIndex];
            }
        }
        
        $this->allImages = array_values($reorderedImages);
    }

    /**
     * Get the count of images that need to be uploaded (new images)
     */
    public function getNewImageCount(): int
    {
        $count = 0;
        foreach ($this->allImages as $image) {
            if ($image['type'] === 'new') {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Get images filtered by type for display
     */
    public function getDisplayImages(): array
    {
        return $this->allImages;
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
            
            $imageUploadService = new ImageUploadService();
            $finalImages = [];
            
            // Process all images in order
            foreach ($this->allImages as $index => $imageItem) {
                $type = $imageItem['type'];
                $data = $imageItem['data'];
                
                if ($type === 'existing') {
                    // Keep existing image, just update order and is_primary
                    $data['order'] = $index + 1;
                    $data['is_primary'] = $index === 0;
                    
                    // Fix URL if needed
                    if (isset($data['path']) && !isset($data['url'])) {
                        $data['url'] = Storage::url($data['path']);
                    }
                    if (isset($data['url']) && strpos($data['url'], '127.0.0.1:8000') !== false) {
                        $data['url'] = str_replace('127.0.0.1:8000', 'localhost', $data['url']);
                    }
                    
                    $finalImages[] = $data;
                } else {
                    // Upload new image
                    $file = $data['temporary_file'] ?? null;
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        $uploadedImage = $imageUploadService->uploadPropertyImageForEdit(
                            $file,
                            $userId,
                            $propertyId,
                            $index + 1
                        );
                        
                        if ($uploadedImage) {
                            $uploadedImage['is_primary'] = $index === 0;
                            $finalImages[] = $uploadedImage;
                        }
                    }
                }
            }
            
            // Apply watermark if enabled
            if ($this->watermark_enabled) {
                foreach ($finalImages as &$img) {
                    if (!($img['is_watermarked'] ?? false)) {
                        $imageUploadService->applyWatermark($img['path']);
                        $img['is_watermarked'] = true;
                    }
                }
                unset($img);
            }
            
            // Delete marked images from storage
            if (!empty($this->deletedImagePaths)) {
                $imageUploadService->deletePropertyImages($this->deletedImagePaths);
            }

            // Update the property
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
                'amenities' => $this->normalizeAmenitiesForSave($this->amenities),
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
            
            $property->images = $finalImages;
            $property->save();

            // Emit event to refresh inventory
            $this->dispatch('property-updated', propertyId: $this->property['id'], data: $property->toArray());

            $this->closeModal();
            session()->flash('success', 'Property updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', 'Validation failed: ' . implode(', ', $e->errors()));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            session()->flash('error', 'Property not found.');
        } catch (\Exception $error) {
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
