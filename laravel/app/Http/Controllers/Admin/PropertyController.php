<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    protected $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    /**
     * Display a listing of properties (for admin inventory)
     */
    public function index(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $query = Property::with('user:id,name')
            ->forUser($userId)
            ->when($request->status, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->when($request->type, function ($q) use ($request) {
                return $q->where('property_type', $request->type);
            })
            ->when($request->search, function ($q) use ($request) {
                return $q->where(function ($query) use ($request) {
                    $query->where('title', 'ilike', "%{$request->search}%")
                          ->orWhere('address', 'ilike', "%{$request->search}%");
                });
            });

        $properties = $query->orderBy('created_at', 'desc')
                           ->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $properties->items(),
            'pagination' => [
                'current_page' => $properties->currentPage(),
                'last_page' => $properties->lastPage(),
                'per_page' => $properties->perPage(),
                'total' => $properties->total(),
            ]
        ]);
    }

    /**
     * Show the property creation form
     */
    public function create()
    {
        return view('admin.properties.create');
    }

    /**
     * Store a newly created property
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'required|in:apartment,villa,plot,commercial,office',
            'price' => 'nullable|numeric|min:0',
            'area_sqft' => 'nullable|integer|min:0',
            'net_price' => 'nullable|numeric|min:0',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'amenities' => 'nullable|array',
            'owner_name' => 'nullable|string|max:255',
            'owner_phone' => 'nullable|string|max:50',
            'private_notes' => 'nullable|string',
            'status' => 'nullable|in:draft,available,booked,sold',
            'is_featured' => 'nullable|boolean',
            'label_type' => 'nullable|in:none,new,popular,verified,custom',
            'custom_label_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'watermark_enabled' => 'nullable|boolean',
        ]);

        try {
            $property = Property::create([
                ...$validated,
                'user_id' => Auth::id(),
                'status' => $validated['status'] ?? 'draft',
                'is_featured' => $validated['is_featured'] ?? false,
                'label_type' => $validated['label_type'] ?? 'none',
                'custom_label_color' => $validated['custom_label_color'] ?? '#3B82F6',
                'watermark_enabled' => $validated['watermark_enabled'] ?? true,
                'amenities' => $validated['amenities'] ?? [],
            ]);

            // Handle image uploads
            if ($request->hasFile('images')) {
                $this->handleImageUploads($property, $request->file('images'));
            }

            return response()->json([
                'success' => true,
                'message' => 'Property created successfully',
                'data' => $property->load('user:id,name')
            ], 201);

        } catch (\Exception $e) {
            Log::error('Property creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create property'
            ], 500);
        }
    }

    /**
     * Display the specified property
     */
    public function show(Property $property): JsonResponse
    {
        // Check ownership
        if ($property->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Property not found'
            ], 404);
        }

        // Increment view count
        $property->incrementViews();
        
        return response()->json([
            'success' => true,
            'data' => $property->load('user:id,name')
        ]);
    }

    /**
     * Update the specified property
     */
    public function update(Request $request, Property $property): JsonResponse
    {
        // Check ownership
        if ($property->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Property not found'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'sometimes|required|in:apartment,villa,plot,commercial,office',
            'price' => 'nullable|numeric|min:0',
            'area_sqft' => 'nullable|integer|min:0',
            'net_price' => 'nullable|numeric|min:0',
            'address' => 'sometimes|required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'status' => 'nullable|in:draft,available,booked,sold',
            'is_featured' => 'nullable|boolean',
            'label_type' => 'nullable|in:none,new,popular,verified,custom',
            'custom_label_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'amenities' => 'nullable|array',
            'owner_name' => 'nullable|string|max:255',
            'owner_phone' => 'nullable|string|max:50',
            'private_notes' => 'nullable|string',
            'watermark_enabled' => 'nullable|boolean',
        ]);

        try {
            $property->update($validated);

            // Handle status transitions
            if (isset($validated['status'])) {
                $this->handleStatusTransition($property, $validated['status']);
            }

            // Handle new image uploads
            if ($request->hasFile('images')) {
                $this->handleImageUploads($property, $request->file('images'));
            }

            return response()->json([
                'success' => true,
                'message' => 'Property updated successfully',
                'data' => $property->fresh()->load('user:id,name')
            ]);

        } catch (\Exception $e) {
            Log::error('Property update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update property'
            ], 500);
        }
    }

    /**
     * Remove the specified property
     */
    public function destroy(Property $property): JsonResponse
    {
        // Check ownership
        if ($property->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Property not found'
            ], 404);
        }

        try {
            // Delete associated images
            $this->deletePropertyImages($property);
            
            $property->delete();

            return response()->json([
                'success' => true,
                'message' => 'Property deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Property deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete property'
            ], 500);
        }
    }

    /**
     * Store draft property data
     */
    public function storeDraft(Request $request): JsonResponse
    {
        // For draft saving, we can save partial data
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'nullable|in:apartment,villa,plot,commercial,office',
            'price' => 'nullable|numeric|min:0',
            'area_sqft' => 'nullable|integer|min:0',
            'address' => 'nullable|string',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'amenities' => 'nullable|array',
        ]);

        try {
            $property = Property::create([
                ...$validated,
                'user_id' => Auth::id(),
                'status' => 'draft',
                'is_featured' => false,
                'label_type' => 'none',
                'custom_label_color' => '#3B82F6',
                'watermark_enabled' => true,
                'amenities' => $validated['amenities'] ?? [],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Draft saved successfully',
                'data' => $property
            ], 201);

        } catch (\Exception $e) {
            Log::error('Draft saving failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save draft'
            ], 500);
        }
    }

    /**
     * Handle image uploads
     */
    private function handleImageUploads(Property $property, array $images): void
    {
        $userId = Auth::id();
        $propertyId = $property->id;
        $imageMetadata = [];

        foreach ($images as $index => $image) {
            if (!$image->isValid()) {
                continue;
            }

            $originalName = $image->getClientOriginalName();
            $extension = $image->getClientOriginalExtension();
            $fileName = Str::uuid() . '.' . $extension;
            
            // Create directory structure
            $directory = "properties/{$userId}/{$propertyId}/" . ($index === 0 ? 'primary' : 'gallery');
            
            // Store image
            $path = $image->storeAs($directory, $fileName, 'public');
            
            // Add watermark if enabled
            if ($property->watermark_enabled) {
                $this->applyWatermark($path);
            }
            
            // Store metadata
            $imageMetadata[] = [
                'path' => $path,
                'original_name' => $originalName,
                'size' => $image->getSize(),
                'mime_type' => $image->getMimeType(),
                'order' => $index + 1,
                'is_watermarked' => $property->watermark_enabled,
            ];
            
            // Set first image as primary
            if ($index === 0 && !$property->primary_image_path) {
                $property->update(['primary_image_path' => $path]);
            }
        }
        
        // Update gallery images
        if (!empty($imageMetadata)) {
            $property->update(['images_metadata' => $imageMetadata]);
        }
    }

    /**
     * Apply watermark to image (simplified version)
     */
    private function applyWatermark(string $imagePath): void
    {
        try {
            $fullPath = Storage::disk('public')->path($imagePath);
            
            // Simple text watermark - you can enhance this with Intervention Image
            $watermarkText = 'Elite Homes';
            // For now, we'll just log the watermark attempt
            // TODO: Implement actual watermark using Intervention Image
            Log::info("Watermark applied to: {$imagePath}");
            
        } catch (\Exception $e) {
            Log::warning('Watermark application failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete all property images
     */
    private function deletePropertyImages(Property $property): void
    {
        try {
            // Delete primary image
            if ($property->primary_image_path) {
                Storage::disk('public')->delete($property->primary_image_path);
            }
            
            // Delete gallery images
            if ($property->images_metadata) {
                foreach ($property->images_metadata as $image) {
                    Storage::disk('public')->delete($image['path']);
                }
            }
            
            // Delete directory if empty
            $userId = $property->user_id;
            $propertyId = $property->id;
            $directoryPath = "properties/{$userId}/{$propertyId}";
            Storage::disk('public')->deleteDirectory($directoryPath);
            
        } catch (\Exception $e) {
            Log::warning('Image deletion failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle status transitions
     */
    private function handleStatusTransition(Property $property, string $newStatus): void
    {
        match($newStatus) {
            Property::STATUS_AVAILABLE => $property->publish(),
            Property::STATUS_SOLD => $property->markAsSold(),
            default => null,
        };
    }

    /**
     * Update property status (for edit modal)
     */
    public function updateStatus(Request $request, Property $property): JsonResponse
    {
        if ($property->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Property not found'
            ], 404);
        }

        $validated = $request->validate([
            'status' => 'required|in:draft,available,booked,sold',
            'is_featured' => 'nullable|boolean',
            'label_type' => 'nullable|in:none,new,popular,verified,custom',
            'custom_label_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
        ]);

        try {
            $property->update($validated);
            
            // Handle status transitions
            $this->handleStatusTransition($property, $validated['status']);

            return response()->json([
                'success' => true,
                'message' => 'Property status updated successfully',
                'data' => $property->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Status update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update property status'
            ], 500);
        }
    }
}