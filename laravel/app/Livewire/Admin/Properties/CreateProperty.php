<?php

namespace App\Livewire\Admin\Properties;

use App\Models\Property;
use App\Data\AmenitiesData;
use App\Services\ImageUploadService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreateProperty extends Component
{
    use WithFileUploads;

    // Step management
    public $currentStep = 0;
    public $showExitModal = false;
    public $isSubmitting = false;
    public $validationErrors = [];

    // Form data
    public $title = '';
    public $type = 'apartment';
    public $price = '';
    public $area = '';
    public $bedrooms = 3;
    public $bathrooms = 2;
    public $description = '';
    public $amenities = [];
    public $address = '';
    public $latitude = '';
    public $longitude = '';
    public $mapsEmbedUrl = '';
    public $watermark = true;
    public $ownerName = '';
    public $ownerPhone = '';
    public $netPrice = '';
    public $privateNotes = '';

    // File uploads
    public $images = [];
    public $newImages = [];

    // Map loading state
    public $isLoadingMap = false;

    // Button loading states
    public $isSavingDraft = false;
    public $isProcessingStep = false;
    public $isPublishing = false;
    public $draftSaved = false;
    public $draftSavedAt = null;

    // Static data
    public $steps = ['Basics', 'Location', 'Media', 'Vault'];
    public $stepTitles = ['Basics', 'Location', 'Media', 'Private Vault'];

    public $propertyTypes = [
        ['value' => 'apartment', 'label' => 'Apartment', 'icon' => 'apartment'],
        ['value' => 'villa', 'label' => 'Villa', 'icon' => 'villa'],
        ['value' => 'plot', 'label' => 'Plot', 'icon' => 'landscape'],
        ['value' => 'commercial', 'label' => 'Commercial', 'icon' => 'storefront'],
        ['value' => 'office', 'label' => 'Office', 'icon' => 'business_center']
    ];

    // Advanced amenities system
    public $amenitiesSearch = '';
    public $showAmenitiesDropdown = false;
    public $availableAmenities = [];

    protected $listeners = [
        'exitConfirmed' => 'handleExit',
    ];

    public function mount()
    {
        // Initialize amenities data
        $this->availableAmenities = AmenitiesData::getAll();

        // Try to load draft from database first, then fallback to session
        $this->loadDraftFromDatabase();
        
        // If no database draft, load from session as backup
        if (empty($this->title)) {
            $this->loadDraft();
        }
    }

    /**
     * Load draft data from database if exists
     */
    protected function loadDraftFromDatabase()
    {
        $draftId = session('draft_property_id');
        
        if ($draftId) {
            $property = Property::where('id', $draftId)
                ->where('user_id', Auth::id())
                ->where('status', 'draft')
                ->first();
            
            if ($property) {
                $this->title = $property->title ?? '';
                $this->type = $property->property_type ?? 'apartment';
                $this->price = $property->price ?? '';
                $this->area = $property->area_sqft ?? '';
                $this->bedrooms = $property->bedrooms ?? 3;
                $this->bathrooms = $property->bathrooms ?? 2;
                $this->description = $property->description ?? '';
                $this->amenities = $property->amenities ?? [];
                $this->address = $property->address ?? '';
                $this->latitude = $property->latitude ?? '';
                $this->longitude = $property->longitude ?? '';
                $this->mapsEmbedUrl = $property->maps_embed_url ?? '';
                $this->watermark = $property->watermark_enabled ?? true;
                $this->ownerName = $property->owner_name ?? '';
                $this->ownerPhone = $property->owner_phone ?? '';
                $this->netPrice = $property->net_price ?? '';
                $this->privateNotes = $property->private_notes ?? '';
                $this->draftSaved = true;
                $this->draftSavedAt = $property->updated_at ? $property->updated_at->format('M d, Y h:i A') : null;
                return true;
            }
        }
        return false;
    }

    // Navigation methods
    public function nextStep()
    {
        $this->isProcessingStep = true;

        if (!$this->validateCurrentStep()) {
            $this->isProcessingStep = false;
            return;
        }

        if ($this->currentStep < 3) {
            $this->currentStep++;
            $this->saveDraft();
        }

        $this->isProcessingStep = false;
    }

    public function previousStep()
    {
        if ($this->currentStep > 0) {
            $this->currentStep--;
            $this->saveDraft();
        }
    }

    // Validation
    public function validateCurrentStep()
    {
        $this->validationErrors = [];

        switch ($this->currentStep) {
            case 0: // Basics step
                if (empty(trim($this->title))) {
                    $this->validationErrors['title'] = 'Property title is required';
                    return false;
                }
                if (empty($this->type)) {
                    $this->validationErrors['type'] = 'Property type is required';
                    return false;
                }
                break;

            case 1: // Location step
                // Address is optional
                // Validate coordinates if provided
                if (!empty($this->latitude) && !is_numeric($this->latitude)) {
                    $this->validationErrors['latitude'] = 'Invalid latitude format';
                    return false;
                }
                if (!empty($this->longitude) && !is_numeric($this->longitude)) {
                    $this->validationErrors['longitude'] = 'Invalid longitude format';
                    return false;
                }
                break;

            case 2: // Media step
                // Images are optional
                break;

            case 3: // Vault step
                // Private fields are optional
                break;
        }

        return true;
    }

    // Counter methods
    public function incrementBedrooms()
    {
        $this->bedrooms++;
        $this->saveDraft();
    }

    public function decrementBedrooms()
    {
        if ($this->bedrooms > 0) {
            $this->bedrooms--;
            $this->saveDraft();
        }
    }

    public function incrementBathrooms()
    {
        $this->bathrooms++;
        $this->saveDraft();
    }

    public function decrementBathrooms()
    {
        if ($this->bathrooms > 0) {
            $this->bathrooms--;
            $this->saveDraft();
        }
    }

    public function setPropertyType($type)
    {
        $this->type = $type;
        $this->saveDraft();
    }

    // Advanced amenities methods
    public function updatedAmenitiesSearch()
    {
        $this->showAmenitiesDropdown = !empty($this->amenitiesSearch);
    }

    /**
     * Normalize amenities array to ensure it's a clean indexed array
     */
    protected function normalizeAmenities()
    {
        // Ensure amenities is an array
        if (!is_array($this->amenities)) {
            $this->amenities = [];
        }

        // Filter out empty/null values and reindex
        $this->amenities = array_values(array_filter($this->amenities, function ($amenity) {
            return $amenity !== null && $amenity !== '' && trim($amenity) !== '';
        }));
    }

    /**
     * Validate that amenities is a valid indexed array
     */
    protected function validateAmenities()
    {
        // Ensure amenities is an array
        if (!is_array($this->amenities)) {
            $this->amenities = [];
            return;
        }

        // Ensure it's a sequential indexed array (not associative)
        $keys = array_keys($this->amenities);
        if (array_keys($keys) !== $keys) {
            // It's an associative array, convert to indexed
            $this->amenities = array_values($this->amenities);
        }

        // Normalize to remove any corrupted data
        $this->normalizeAmenities();
    }

    public function addAmenity($amenityName = null)
    {
        // Normalize amenities first
        $this->validateAmenities();

        // If specific amenity name provided (from dropdown), add it
        if ($amenityName) {
            if (!in_array($amenityName, $this->amenities, true)) {
                $this->amenities[] = $amenityName;
            }
        }
        // If no amenity name but search has text, add it as custom amenity
        elseif (!empty(trim($this->amenitiesSearch))) {
            $customAmenity = trim($this->amenitiesSearch);
            if (!in_array($customAmenity, $this->amenities, true)) {
                $this->amenities[] = $customAmenity;
            }
        }

        // Reset search and close dropdown
        $this->amenitiesSearch = '';
        $this->showAmenitiesDropdown = false;
        $this->saveDraft();
    }

    public function removeAmenity($amenityName)
    {
        // Validate amenities is an indexed array
        $this->validateAmenities();

        // Remove by amenity name only
        $this->amenities = array_filter($this->amenities, function ($amenity) use ($amenityName) {
            return $amenity !== $amenityName;
        });

        // Reindex array
        $this->amenities = array_values($this->amenities);
        $this->saveDraft();
    }

    // File handling
    public function updatedImages()
    {
        // Only validate and save if we actually have images
        if (!empty($this->images)) {
            $this->validateImages();
            $this->saveDraft();
        }
    }

    public function updatedNewImages()
    {
        // Append new images to the main images array
        if (!empty($this->newImages)) {
            foreach ($this->newImages as $newImage) {
                $this->images[] = $newImage;
            }
            // Clear the newImages array
            $this->newImages = [];
            // Validate and save
            $this->validateImages();
            $this->saveDraft();
        }
    }

    public function removeImage($index)
    {
        if (isset($this->images[$index])) {
            unset($this->images[$index]);
            $this->images = array_values($this->images); // Reindex array
            $this->saveDraft();
        }
    }


    private function validateImages()
    {
        $maxImages = 10;
        $maxSize = 10 * 1024 * 1024; // 10MB

        if (count($this->images) > $maxImages) {
            $this->addError('images', "Maximum {$maxImages} images allowed");
            return false;
        }

        foreach ($this->images as $image) {
            if ($image->getSize() > $maxSize) {
                $this->addError('images', "File '{$image->getClientOriginalName()}' is too large. Maximum size is 10MB.");
                return false;
            }
        }

        return true;
    }

    // Map integration
    public function loadMap()
    {
        if (empty($this->latitude) || empty($this->longitude)) {
            session()->flash('error', 'Please enter latitude and longitude to load the map');
            return;
        }

        $lat = floatval($this->latitude);
        $lng = floatval($this->longitude);

        if ($lat < -90 || $lat > 90) {
            session()->flash('error', 'Latitude must be between -90 and 90');
            return;
        }

        if ($lng < -180 || $lng > 180) {
            session()->flash('error', 'Longitude must be between -180 and 180');
            return;
        }

        $this->isLoadingMap = true;

        // Generate Google Maps embed URL
        $mapsUrl = "https://maps.google.com/maps?q={$lat},{$lng}&z=15&output=embed&t=&z=15&ie=UTF8&iwloc=&output=embed&maptype=satellite&source=embed&disableDefaultUI=true&zoomControl=false&mapTypeControl=false&streetViewControl=false&rotateControl=false&fullscreenControl=false&scrollwheel=false";

        $this->mapsEmbedUrl = $mapsUrl;
        $this->isLoadingMap = false;
        $this->saveDraft();

        session()->flash('success', 'Map loaded successfully');
    }

    // Draft management
    public function saveDraft()
    {
        // Normalize amenities before saving
        $this->normalizeAmenities();

        $draftData = [
            'title' => $this->title,
            'type' => $this->type,
            'price' => $this->price,
            'area' => $this->area,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'description' => $this->description,
            'amenities' => $this->amenities,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'mapsEmbedUrl' => $this->mapsEmbedUrl,
            'watermark' => $this->watermark,
            'ownerName' => $this->ownerName,
            'ownerPhone' => $this->ownerPhone,
            'netPrice' => $this->netPrice,
            'privateNotes' => $this->privateNotes,
            'currentStep' => $this->currentStep,
        ];

        session(['propertyDraft' => $draftData]);
    }

    public function loadDraft()
    {
        $draft = session('propertyDraft');
        if ($draft) {
            $this->title = $draft['title'] ?? '';
            $this->type = $draft['type'] ?? 'apartment';
            $this->price = $draft['price'] ?? '';
            $this->area = $draft['area'] ?? '';
            $this->bedrooms = $draft['bedrooms'] ?? 3;
            $this->bathrooms = $draft['bathrooms'] ?? 2;
            $this->description = $draft['description'] ?? '';
            $this->amenities = $draft['amenities'] ?? [];
            $this->address = $draft['address'] ?? '';
            $this->latitude = $draft['latitude'] ?? '';
            $this->longitude = $draft['longitude'] ?? '';
            $this->mapsEmbedUrl = $draft['mapsEmbedUrl'] ?? '';
            $this->watermark = $draft['watermark'] ?? true;
            $this->ownerName = $draft['ownerName'] ?? '';
            $this->ownerPhone = $draft['ownerPhone'] ?? '';
            $this->netPrice = $draft['netPrice'] ?? '';
            $this->privateNotes = $draft['privateNotes'] ?? '';
            $this->currentStep = $draft['currentStep'] ?? 0;
        }
    }

    public function saveAsDraft()
    {
        // Validate that title is present
        if (empty(trim($this->title))) {
            session()->flash('error', 'Please enter a property title before saving as draft.');
            return;
        }

        $this->isSavingDraft = true;

        try {
            // Normalize amenities before saving
            $this->normalizeAmenities();

            // Create or update property with draft status
            $propertyData = [
                'title' => $this->title,
                'property_type' => $this->type ?? 'apartment',
                'price' => $this->price ?: null,
                'area_sqft' => $this->area ?: null,
                'bedrooms' => $this->bedrooms,
                'bathrooms' => $this->bathrooms,
                'description' => $this->description ?: null,
                'amenities' => $this->amenities,
                'address' => $this->address ?: null,
                'latitude' => $this->latitude ?: null,
                'longitude' => $this->longitude ?: null,
                'maps_embed_url' => $this->mapsEmbedUrl ?: null,
                'owner_name' => $this->ownerName ?: null,
                'owner_phone' => $this->ownerPhone ?: null,
                'net_price' => $this->netPrice ?: null,
                'private_notes' => $this->privateNotes ?: null,
                'watermark_enabled' => $this->watermark,
                'status' => 'draft',
                'user_id' => Auth::id(),
                'updated_at' => now(),
            ];

            // Check if we have an existing draft ID from session
            $draftId = session('draft_property_id');

            if ($draftId) {
                // Update existing draft
                $property = Property::where('id', $draftId)->where('user_id', Auth::id())->first();
                if ($property) {
                    $property->update($propertyData);
                    // Handle images if any uploaded
                    if (!empty($this->images)) {
                        $this->processImages($property);
                    }
                } else {
                    // Draft not found, create new
                    $property = Property::create($propertyData);
                    session(['draft_property_id' => $property->id]);
                    if (!empty($this->images)) {
                        $this->processImages($property);
                    }
                }
            } else {
                // Create new draft
                $property = Property::create($propertyData);
                session(['draft_property_id' => $property->id]);
                if (!empty($this->images)) {
                    $this->processImages($property);
                }
            }

            // Update session draft data as backup
            $this->saveDraft();

            // Set success state
            $this->draftSaved = true;
            $this->draftSavedAt = now()->format('M d, Y h:i A');

            session()->flash('success', 'Draft saved successfully! You can continue editing later.');

        } catch (\Exception $e) {
            Log::error('Draft save failed: ' . $e->getMessage());
            session()->flash('error', 'Failed to save draft. Please try again.');
        } finally {
            $this->isSavingDraft = false;
        }
    }

    // Form submission
    public function publishLive()
    {
        if ($this->isSubmitting) return;

        $this->isPublishing = true;
        $this->isSubmitting = true;

        try {
            // Validate all steps
            for ($step = 0; $step < 4; $step++) {
                $this->currentStep = $step;
                if (!$this->validateCurrentStep()) {
                    session()->flash('error', 'Please fix the validation errors before publishing');
                    return;
                }
            }

            // Normalize amenities before saving
            $this->normalizeAmenities();

            // Create property
            $property = Property::create([
                'title' => $this->title,
                'property_type' => $this->type,
                'price' => $this->price ?: null,
                'area_sqft' => $this->area ?: null,
                'bedrooms' => $this->bedrooms,
                'bathrooms' => $this->bathrooms,
                'description' => $this->description ?: null,
                'amenities' => $this->amenities,
                'address' => $this->address ?: null,
                'latitude' => $this->latitude ?: null,
                'longitude' => $this->longitude ?: null,
                'maps_embed_url' => $this->mapsEmbedUrl ?: null,
                'owner_name' => $this->ownerName ?: null,
                'owner_phone' => $this->ownerPhone ?: null,
                'net_price' => $this->netPrice ?: null,
                'private_notes' => $this->privateNotes ?: null,
                'watermark_enabled' => $this->watermark,
                'status' => 'available',
                'user_id' => Auth::id(),
                'published_at' => now(),
            ]);

            // Handle image uploads
            if (!empty($this->images)) {
                $this->processImages($property);
            }

            // Clear draft
            session()->forget('propertyDraft');

            session()->flash('success', 'Property published successfully!');
            return redirect()->route('admin.inventory');

        } catch (\Exception $e) {
            Log::error('Property creation failed: ' . $e->getMessage());
            session()->flash('error', 'Failed to publish property. Please try again.');
        } finally {
            $this->isSubmitting = false;
            $this->isPublishing = false;
        }
    }

    private function processImages(Property $property)
    {
        $imageUploadService = app(ImageUploadService::class);
        $uploadedImages = [];

        foreach ($this->images as $index => $image) {
            // Validate image
            $validation = $imageUploadService->validateImage($image);
            if (!$validation['valid']) {
                Log::warning('Invalid image upload: ' . implode(', ', $validation['errors']));
                continue;
            }

            // Upload image
            $uploadResult = $imageUploadService->uploadPropertyImages(
                $image,
                $property->user_id,
                $property->id,
                $index + 1
            );

            // Apply watermark if enabled
            if ($this->watermark) {
                $imageUploadService->applyWatermark($uploadResult['path']);
            }

            $uploadedImages[] = $uploadResult;
        }

        // Save images to property
        if (!empty($uploadedImages)) {
            $property->saveImages($uploadedImages, $this->watermark);

            // Set primary image
            if (isset($uploadedImages[0])) {
                $property->update(['primary_image_path' => $uploadedImages[0]['path']]);
            }
        }
    }

    public function showExitModal()
    {
        $this->showExitModal = true;
    }

    public function hideExitModal()
    {
        $this->showExitModal = false;
    }

    public function handleExit()
    {
        $this->saveDraft();
        return redirect()->route('admin.inventory');
    }

    public function render()
    {
        return view('livewire.admin.properties.create-property')
            ->layout('layouts.admin');
    }
}
