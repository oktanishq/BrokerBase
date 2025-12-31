<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;

class PropertyController extends Controller
{
    /**
     * Get all available properties for the frontend
     */
    public function getProperties()
    {
        try {
            $properties = Property::available()
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $properties->map(function ($property) {
                    return [
                        'id' => $property->id,
                        'title' => $property->title,
                        'price' => $property->formatted_price,
                        'location' => $property->location,
                        'image' => $property->image,
                        'views' => $property->views,
                        'bedrooms' => $property->bedrooms,
                        'bathrooms' => $property->bathrooms,
                        'sqft' => $property->formatted_area,
                        'type' => $property->type_label,
                        'status' => $property->status,
                        'is_featured' => $property->is_featured,
                        'label' => $property->label_type,
                        'custom_label_color' => $property->custom_label_color,
                        'label_badge' => $property->label_badge,
                        'owner_name' => $property->owner_name,
                        'owner_phone' => $property->owner_phone,
                        'net_price' => $property->net_price,
                        'created_at' => $property->created_at,
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch properties',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single property by ID for the frontend
     */
    public function getProperty($id)
    {
        try {
            $property = Property::available()->find($id);

            if (!$property) {
                return response()->json([
                    'success' => false,
                    'message' => 'Property not found'
                ], 404);
            }

            // Increment view count
            $property->incrementViews();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $property->id,
                    'name' => $property->title,
                    'price' => $property->price,
                    'formatted_price' => $property->formatted_price,
                    'location' => $property->location,
                    'beds' => $property->bedrooms,
                    'baths' => $property->bathrooms,
                    'sqft' => $property->area_sqft,
                    'floor' => 'Ground', // Default floor as it's not in the schema
                    'status' => ucfirst($property->status),
                    'description' => $property->description,
                    'amenities' => $property->amenities ?? [],
                    'images' => $property->all_images,
                    'image_url' => $property->primary_image_url,
                    'map_image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCQF3U8wwqWahzqydH8tpCM9mKKqGUhz-f-IMll0FkwDcp4nlX07epV-AneVFXxuYmLtB4kPr9rgzSOnJMQ1vk6j6TvHgO7GAVG_-D29HeqQOcJhEjqlR6x2NqQqNnlbnN8BFsPN6_WgbRg9JMPth8k-xtki32fTrshtNqgmGeozCjPAWOe7jKjLW4phYIc2pAdFwlCXrxtYrH8mNTDM9ypz3GYPkTpkt6sLtDbW_VHjYDrh3FH-b2VPqHBEmI5nwPI19un-Qi8hj1n',
                    'agent_name' => 'Elite Homes',
                    'agent_logo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCZGvkDKAF1w7WbeFeUNmOM3NRSjHgmhSeryZM7vDVZ1m4ipcSRXPbXSEd2id5wazq_oIOrOECQqI9YWyoWlbbH2hXEX33P14Q3zghNi1ql4tBZGpuTE5NvyUY4ZTQJBmwaOlHrNFtmKJZ5hlyLxVkDdbsRnUKh523LtkEq96u8kK6SNVuz5caz2ymq71nBnay5rA4-tCzvVqaPnmBNsnRYGgYVWooVyVl0TRj85yqteKd7hSy3zjvwglp6ZBELj2yif6o7tUd4K-Hz',
                    'latitude' => $property->latitude,
                    'longitude' => $property->longitude,
                    'created_at' => $property->created_at,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch property',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show property details page (web route)
     */
    public function show($id)
    {
        try {
            $property = Property::available()->find($id);

            if (!$property) {
                abort(404);
            }

            // Increment view count
            $property->incrementViews();

            // Transform property data to match the expected format in the detail view
            $propertyData = (object) [
                'id' => $property->id,
                'name' => $property->title,
                'price' => $property->price,
                'location' => $property->location,
                'beds' => $property->bedrooms,
                'baths' => $property->bathrooms,
                'sqft' => $property->area_sqft,
                'floor' => 'Ground', // Default as it's not in schema
                'status' => ucfirst($property->status),
                'description' => $property->description,
                'amenities' => $property->amenities ?? [],
                'images' => $property->all_images,
                'image_url' => $property->primary_image_url,
                'image_alt' => $property->title . ' - Property image',
                'map_image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCQF3U8wwqWahzqydH8tpCM9mKKqGUhz-f-IMll0FkwDcp4nlX07epV-AneVFXxuYmLtB4kPr9rgzSOnJMQ1vk6j6TvHgO7GAVG_-D29HeqQOcJhEjqlR6x2NqQqNnlbnN8BFsPN6_WgbRg9JMPth8k-xtki32fTrshtNqgmGeozCjPAWOe7jKjLW4phYIc2pAdFwlCXrxtYrH8mNTDM9ypz3GYPkTpkt6sLtDbW_VHjYDrh3FH-b2VPqHBEmI5nwPI19un-Qi8hj1n',
                'agent_name' => 'Elite Homes',
                'agent_logo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCZGvkDKAF1w7WbeFeUNmOM3NRSjHgmhSeryZM7vDVZ1m4ipcSRXPbXSEd2id5wazq_oIOrOECQqI9YWyoWlbbH2hXEX33P14Q3zghNi1ql4tBZGpuTE5NvyUY4ZTQJBmwaOlHrNFtmKJZ5hlyLxVkDdbsRnUKh523LtkEq96u8kK6SNVuz5caz2ymq71nBnay5rA4-tCzvVqaPnmBNsnRYGgYVWooVyVl0TRj85yqteKd7hSy3zjvwglp6ZBELj2yif6o7tUd4K-Hz',
            ];

            return view('property.detail', compact('property'));
        } catch (\Exception $e) {
            abort(404);
        }
    }
}