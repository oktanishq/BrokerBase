<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'property_type',
        'price',
        'area_sqft',
        'net_price',
        'address',
        'latitude',
        'longitude',
        'maps_embed_url',
        'bedrooms',
        'bathrooms',
        'status',
        'is_featured',
        'label_type',
        'custom_label_color',
        'views_count',
        'amenities',
        'owner_name',
        'owner_phone',
        'private_notes',
        'primary_image_path',
        'images_metadata',
        'watermark_enabled',
        'user_id',
        'published_at',
        'sold_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'net_price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'is_featured' => 'boolean',
        'watermark_enabled' => 'boolean',
        'views_count' => 'integer',
        'amenities' => 'array',
        'images_metadata' => 'array',
        'published_at' => 'datetime',
        'sold_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $dates = [
        'published_at',
        'sold_at',
        'deleted_at',
    ];

    /**
     * Constants for property types
     */
    const PROPERTY_TYPES = [
        'apartment' => 'Apartment',
        'villa' => 'Villa',
        'plot' => 'Plot',
        'commercial' => 'Commercial',
        'office' => 'Office',
    ];

    /**
     * Constants for status
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_AVAILABLE = 'available';
    const STATUS_BOOKED = 'booked';
    const STATUS_SOLD = 'sold';

    /**
     * Constants for label types
     */
    const LABEL_NONE = 'none';
    const LABEL_NEW = 'new';
    const LABEL_POPULAR = 'popular';
    const LABEL_VERIFIED = 'verified';
    const LABEL_CUSTOM = 'custom';

    /**
     * Get the user that owns the property
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all images with metadata and URLs
     */
    public function getAllImagesAttribute(): array
    {
        $images = [];
        
        // Add primary image if exists
        if ($this->primary_image_path) {
            $images[] = [
                'path' => $this->primary_image_path,
                'url' => asset('storage/' . $this->primary_image_path),
                'type' => 'primary',
                'is_primary' => true,
            ];
        }
        
        // Add gallery images
        if ($this->images_metadata) {
            foreach ($this->images_metadata as $image) {
                $images[] = [
                    'path' => $image['path'],
                    'url' => asset('storage/' . $image['path']),
                    'type' => 'gallery',
                    'is_primary' => false,
                    'original_name' => $image['original_name'] ?? null,
                    'size' => $image['size'] ?? null,
                    'mime_type' => $image['mime_type'] ?? null,
                    'order' => $image['order'] ?? 0,
                    'is_watermarked' => $image['is_watermarked'] ?? false,
                ];
            }
        }
        
        return $images;
    }

    /**
     * Get primary image URL
     */
    public function getPrimaryImageUrlAttribute(): ?string
    {
        if (!$this->primary_image_path) {
            return null;
        }
        
        return asset('storage/' . $this->primary_image_path);
    }

    /**
     * Get name (alias for title)
     */
    public function getNameAttribute(): ?string
    {
        return $this->title;
    }

    /**
     * Get location (alias for address)
     */
    public function getLocationAttribute(): ?string
    {
        return $this->address;
    }

    /**
     * Get beds (alias for bedrooms)
     */
    public function getBedsAttribute(): ?int
    {
        return $this->bedrooms;
    }

    /**
     * Get baths (alias for bathrooms)
     */
    public function getBathsAttribute(): ?int
    {
        return $this->bathrooms;
    }

    /**
     * Get sqft (alias for area_sqft)
     */
    public function getSqftAttribute(): ?int
    {
        return $this->area_sqft;
    }

    /**
     * Get image_url (alias for primary_image_url)
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->primary_image_url;
    }

    /**
     * Get views count
     */
    public function getViewsAttribute(): int
    {
        return $this->views_count ?? 0;
    }

    /**
     * Get map image URL based on coordinates
     */
    public function getMapImageUrlAttribute(): string
    {
        if ($this->latitude && $this->longitude) {
            // Generate a dynamic map image URL using Google Static Maps API
            $apiKey = env('GOOGLE_MAPS_API_KEY', '');
            if ($apiKey) {
                $url = "https://maps.googleapis.com/maps/api/staticmap?";
                $url .= "center={$this->latitude},{$this->longitude}&";
                $url .= "zoom=15&";
                $url .= "size=800x600&";
                $url .= "maptype=roadmap&";
                $url .= "markers=color:red%7Clabel:A%7C{$this->latitude},{$this->longitude}&";
                $url .= "key={$apiKey}";
                
                return $url;
            }
        }
        
        // Fallback to a generic location-based map
        return "https://maps.googleapis.com/maps/api/staticmap?center=" . urlencode($this->address ?? 'Dubai') . "&zoom=15&size=800x600&maptype=roadmap&key=" . (env('GOOGLE_MAPS_API_KEY', ''));
    }

    /**
     * Scope for available properties
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Scope for featured properties
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
                    ->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Scope for user properties
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for properties by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('property_type', $type);
    }

    /**
     * Scope for properties by status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Increment view count
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Mark as published
     */
    public function publish(): void
    {
        $this->update([
            'status' => self::STATUS_AVAILABLE,
            'published_at' => now(),
        ]);
    }

    /**
     * Mark as sold
     */
    public function markAsSold(): void
    {
        $this->update([
            'status' => self::STATUS_SOLD,
            'sold_at' => now(),
        ]);
    }

    /**
     * Save images metadata
     */
    public function saveImages(array $images, bool $watermarkEnabled = true): void
    {
        $metadata = [];
        
        foreach ($images as $index => $image) {
            $metadata[] = [
                'path' => $image['path'],
                'original_name' => $image['original_name'] ?? null,
                'size' => $image['size'] ?? null,
                'mime_type' => $image['mime_type'] ?? null,
                'order' => $index + 1,
                'is_watermarked' => $watermarkEnabled,
            ];
        }
        
        $this->update([
            'images_metadata' => $metadata,
            'watermark_enabled' => $watermarkEnabled,
        ]);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        if (!$this->price) {
            return 'Price TBD';
        }
        
        return '$' . number_format($this->price, 0);
    }

    /**
     * Get formatted area
     */
    public function getFormattedAreaAttribute(): string
    {
        if (!$this->area_sqft) {
            return 'N/A';
        }
        
        return number_format($this->area_sqft) . ' sqft';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => 'bg-green-100 text-green-700 border-green-200',
            self::STATUS_SOLD => 'bg-red-100 text-red-700 border-red-200',
            self::STATUS_BOOKED => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            self::STATUS_DRAFT => 'bg-gray-200 text-gray-600 border-gray-300',
            default => 'bg-gray-100 text-gray-600 border-gray-200',
        };
    }

    /**
     * Get label badge data
     */
    public function getLabelBadgeAttribute(): ?array
    {
        if ($this->label_type === 'none') {
            return null;
        }
        
        $labels = [
            'new' => ['label' => 'New Arrival', 'color' => '#3B82F6'],
            'popular' => ['label' => 'Popular', 'color' => '#F59E0B'],
            'verified' => ['label' => 'Verified', 'color' => '#10B981'],
            'custom' => ['label' => 'Custom', 'color' => $this->custom_label_color],
        ];
        
        return $labels[$this->label_type] ?? null;
    }

    /**
     * Get property type label
     */
    public function getTypeLabelAttribute(): string
    {
        return self::PROPERTY_TYPES[$this->property_type] ?? ucfirst($this->property_type);
    }

    /**
     * Get image URL for frontend
     */
    public function getImageAttribute(): ?string
    {
        return $this->primary_image_url;
    }

    /**
     * Get frontend-formatted property data
     */
    public function getFrontendDataAttribute(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'price' => $this->formatted_price,
            'location' => $this->location,
            'image' => $this->image,
            'views' => $this->views,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'sqft' => $this->sqft,
            'type' => $this->type_label,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'label' => $this->label_type,
            'custom_label_color' => $this->custom_label_color,
            'owner_name' => $this->owner_name,
            'owner_phone' => $this->owner_phone,
            'net_price' => $this->net_price,
            'private_notes' => $this->private_notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Get raw property data for editing
     */
    public function getEditDataAttribute(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'property_type' => $this->property_type,
            'price' => $this->price,
            'area_sqft' => $this->area_sqft,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'maps_embed_url' => $this->maps_embed_url,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'amenities' => $this->amenities,
            'watermark_enabled' => $this->watermark_enabled,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'label_type' => $this->label_type,
            'custom_label_color' => $this->custom_label_color,
            'owner_name' => $this->owner_name,
            'owner_phone' => $this->owner_phone,
            'net_price' => $this->net_price,
            'private_notes' => $this->private_notes,
            'image' => $this->primary_image_url,
        ];
    }
}