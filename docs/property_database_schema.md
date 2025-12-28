# Property Management System - Database Schema Design

## Overview
Single-table PostgreSQL schema for comprehensive property management with local image storage.

## Database Table: `properties`

### Table Structure

```sql
CREATE TABLE properties (
    -- Primary Key
    id BIGSERIAL PRIMARY KEY,
    
    -- Core Property Information
    title VARCHAR(255) NOT NULL,
    description TEXT,
    property_type VARCHAR(50) NOT NULL CHECK (property_type IN ('apartment', 'villa', 'plot', 'commercial', 'office')),
    
    -- Pricing & Area
    price DECIMAL(12,2),
    area_sqft INTEGER,
    net_price DECIMAL(12,2), -- Private vault bottom line
    
    -- Location
    address TEXT NOT NULL,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    
    -- Property Specifications
    bedrooms INTEGER DEFAULT 0,
    bathrooms INTEGER DEFAULT 0,
    
    -- Status & Marketing
    status VARCHAR(20) NOT NULL DEFAULT 'draft' CHECK (status IN ('draft', 'available', 'booked', 'sold')),
    is_featured BOOLEAN DEFAULT FALSE,
    label_type VARCHAR(20) DEFAULT 'none' CHECK (label_type IN ('none', 'new', 'popular', 'verified', 'custom')),
    custom_label_color VARCHAR(7) DEFAULT '#3B82F6',
    views_count INTEGER DEFAULT 0,
    
    -- Amenities (JSON array)
    amenities JSONB DEFAULT '[]'::jsonb,
    
    -- Private Vault Information
    owner_name VARCHAR(255),
    owner_phone VARCHAR(50),
    private_notes TEXT,
    
    -- Media Management
    primary_image_path VARCHAR(500), -- Main property image
    images_metadata JSONB DEFAULT '[]'::jsonb, -- Array of additional images with metadata
    watermark_enabled BOOLEAN DEFAULT TRUE,
    
    -- User Management
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    
    -- Timestamps
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    published_at TIMESTAMP WITH TIME ZONE,
    sold_at TIMESTAMP WITH TIME ZONE,
    
    -- Soft delete
    deleted_at TIMESTAMP WITH TIME ZONE
);
```

### Indexes for Performance

```sql
-- Primary search indexes
CREATE INDEX idx_properties_user_id ON properties(user_id);
CREATE INDEX idx_properties_status ON properties(status);
CREATE INDEX idx_properties_property_type ON properties(property_type);
CREATE INDEX idx_properties_is_featured ON properties(is_featured) WHERE status = 'available';

-- Search optimization indexes
CREATE INDEX idx_properties_title_search ON properties USING gin(to_tsvector('english', title));
CREATE INDEX idx_properties_address_search ON properties USING gin(to_tsvector('english', address));

-- Geographic search indexes
CREATE INDEX idx_properties_location ON properties(latitude, longitude);

-- Composite indexes for common queries
CREATE INDEX idx_properties_user_status ON properties(user_id, status);
CREATE INDEX idx_properties_user_featured ON properties(user_id, is_featured) WHERE status = 'available';

-- Timestamp indexes
CREATE INDEX idx_properties_created_at ON properties(created_at DESC);
CREATE INDEX idx_properties_published_at ON properties(published_at DESC) WHERE status = 'available';
```

### Field Definitions & Business Rules

#### Core Property Fields
- **title**: Property listing title (required, max 255 chars)
- **description**: Detailed property description (optional, unlimited length)
- **property_type**: Standardized property categories with enum constraint
- **price**: Public listing price (decimal for precision)
- **area_sqft**: Property area in square feet (integer)
- **net_price**: Private bottom-line price for negotiations

#### Location Fields
- **address**: Full property address (required)
- **latitude/longitude**: Geographic coordinates for map integration
- Both coordinates are optional but recommended for map functionality

#### Property Specifications
- **bedrooms/bathrooms**: Integer counts with sensible defaults
- Minimum value of 0 to accommodate commercial properties

#### Status Management
- **status**: Four-state workflow with database constraints
- **is_featured**: Boolean flag for premium listings
- **label_type**: Marketing labels with predefined options
- **custom_label_color**: Hex color for custom labels
- **views_count**: Track property engagement

#### Amenities Storage
- **amenities**: JSONB array for flexible amenity management
- Supports dynamic amenity lists without schema changes

#### Private Vault (Dealer-Only Data)
- **owner_name/owner_phone**: Property owner contact information
- **private_notes**: Internal dealer notes and negotiation details
- Not visible in public property listings

#### Media Management
- **primary_image_path**: Local filesystem path to main image
- **images_metadata**: JSONB array with additional image information:
  ```json
  [
    {
      "path": "/storage/properties/123/image2.jpg",
      "original_name": "living_room.jpg",
      "size": 2048576,
      "mime_type": "image/jpeg",
      "order": 2,
      "is_watermarked": true
    }
  ]
  ```
- **watermark_enabled**: Control automatic watermarking

#### User Management
- **user_id**: Foreign key to users table for multi-tenant support
- CASCADE delete to maintain data integrity

#### Timestamps
- **created_at/updated_at**: Standard Laravel timestamps
- **published_at**: When property went live
- **sold_at**: When property status changed to 'sold'

### Database Constraints

#### Check Constraints
- Property type must be one of predefined values
- Status must follow valid workflow states
- Label type must be valid option
- Custom label color must be valid hex format

#### Not Null Constraints
- Required fields: title, property_type, address, user_id
- Sensible defaults for optional numeric fields

#### Foreign Key Constraints
- user_id references users table with CASCADE delete
- Ensures data integrity across user properties

### Image Storage Strategy

#### Local Filesystem Structure
```
storage/app/public/properties/
├── {user_id}/
│   ├── {property_id}/
│   │   ├── primary/
│   │   │   └── main_image.jpg
│   │   ├── gallery/
│   │   │   ├── image_1.jpg
│   │   │   ├── image_2.jpg
│   │   │   └── ...
│   │   └── watermarked/
│   │       ├── main_image_watermarked.jpg
│   │       └── ...
```

#### Image Metadata Structure
```json
{
  "primary_image": {
    "path": "/storage/properties/123/primary/main_image.jpg",
    "original_name": "property_hero.jpg",
    "size": 3072000,
    "dimensions": {"width": 1920, "height": 1080},
    "mime_type": "image/jpeg",
    "uploaded_at": "2025-12-28T05:42:08Z"
  },
  "gallery": [
    {
      "path": "/storage/properties/123/gallery/image_1.jpg",
      "original_name": "living_room.jpg",
      "size": 2048576,
      "dimensions": {"width": 1600, "height": 900},
      "mime_type": "image/jpeg",
      "order": 1,
      "is_watermarked": false
    }
  ]
}
```

### Sample Data Types

#### Property Status Examples
- `'draft'` - Property being created/edited
- `'available'` - Live property listing
- `'booked'` - Property reserved but not sold
- `'sold'` - Property transaction completed

#### Label Types
- `'none'` - No special label
- `'new'` - Newly listed property
- `'popular'` - High-engagement property
- `'verified'` - Verified property listing
- `'custom'` - User-defined label with color

#### Property Types
- `'apartment'` - Residential apartment/condo
- `'villa'` - Standalone house/villa
- `'plot'` - Land/development plot
- `'commercial'` - Commercial property
- `'office'` - Office space (specialized commercial)

### Performance Considerations

#### Query Optimization
- Indexes on frequently searched fields
- Full-text search on title and address
- Composite indexes for common filter combinations
- Geographic indexes for location-based searches

#### Storage Efficiency
- JSONB for flexible arrays (amenities, images)
- Proper data types for numeric precision
- Minimal redundancy with proper normalization

#### Scalability
- BIGINT for primary keys (supports growth)
- Proper indexing for high-volume queries
- Soft deletes to maintain data history