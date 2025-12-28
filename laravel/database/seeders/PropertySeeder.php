<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        // Create a test user if none exists
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Sample property data matching the frontend structure
        $sampleProperties = [
            [
                'title' => 'Sunset Villa - Luxury Oceanfront Estate',
                'description' => 'Experience the pinnacle of luxury living in this exclusive oceanfront villa. Offering breathtaking sunset views, premium finishes throughout, and direct beach access.',
                'property_type' => 'villa',
                'price' => 450000,
                'area_sqft' => 1500,
                'address' => 'Business Bay, Dubai',
                'latitude' => 25.2048,
                'longitude' => 55.2708,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'status' => 'available',
                'is_featured' => true,
                'label_type' => 'new',
                'custom_label_color' => '#3B82F6',
                'views_count' => 124,
                'amenities' => ['Swimming Pool', 'Gymnasium', 'Parking', '24/7 Security'],
                'owner_name' => 'Ahmed Al-Rashid',
                'owner_phone' => '+971501234567',
                'net_price' => 420000,
                'private_notes' => 'Owner motivated to sell quickly. Best offer accepted.',
                'watermark_enabled' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'City Apartment - Modern Downtown Living',
                'description' => 'Stunning modern apartment in the heart of downtown with panoramic city views and premium amenities.',
                'property_type' => 'apartment',
                'price' => 850000,
                'area_sqft' => 980,
                'address' => 'Downtown, Seattle',
                'latitude' => 47.6062,
                'longitude' => -122.3321,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'status' => 'sold',
                'is_featured' => false,
                'label_type' => 'popular',
                'custom_label_color' => '#F59E0B',
                'views_count' => 892,
                'amenities' => ['Swimming Pool', 'Parking'],
                'owner_name' => 'Sarah Johnson',
                'owner_phone' => '+12065551234',
                'net_price' => 800000,
                'private_notes' => 'Sold to investor. Quick closing.',
                'watermark_enabled' => true,
                'published_at' => now()->subDays(30),
                'sold_at' => now()->subDays(7),
            ],
            [
                'title' => 'Commercial Office Space',
                'description' => 'Prime commercial office space in the financial district. Perfect for corporate headquarters.',
                'property_type' => 'commercial',
                'price' => null, // Price TBD
                'area_sqft' => 3200,
                'address' => 'Financial District, NY',
                'latitude' => 40.7074,
                'longitude' => -74.0113,
                'bedrooms' => 0, // Not applicable for commercial
                'bathrooms' => 4,
                'status' => 'draft',
                'is_featured' => false,
                'label_type' => 'none',
                'custom_label_color' => '#10B981',
                'views_count' => 0,
                'amenities' => ['24/7 Security', 'Parking'],
                'owner_name' => 'Corporate Holdings LLC',
                'owner_phone' => '+12125559876',
                'net_price' => null,
                'private_notes' => 'Still negotiating lease terms with potential tenant.',
                'watermark_enabled' => true,
            ],
            [
                'title' => 'Luxury Penthouse with Sea View',
                'description' => 'Exclusive penthouse with 360-degree sea views, private elevator access, and luxury finishes throughout.',
                'property_type' => 'apartment',
                'price' => 1200000,
                'area_sqft' => 2200,
                'address' => 'Marina District, Miami',
                'latitude' => 25.7907,
                'longitude' => -80.1300,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'status' => 'available',
                'is_featured' => true,
                'label_type' => 'verified',
                'custom_label_color' => '#10B981',
                'views_count' => 245,
                'amenities' => ['Swimming Pool', 'Gymnasium', 'Parking', '24/7 Security', 'Concierge Service'],
                'owner_name' => 'Maria Gonzalez',
                'owner_phone' => '+13055554321',
                'net_price' => 1150000,
                'private_notes' => 'Premium property. Serious buyers only.',
                'watermark_enabled' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Investment Plot - Development Opportunity',
                'description' => 'Prime land plot with development potential. Zoned for high-rise residential construction.',
                'property_type' => 'plot',
                'price' => 2000000,
                'area_sqft' => 5000,
                'address' => 'Uptown, Austin',
                'latitude' => 30.2672,
                'longitude' => -97.7431,
                'bedrooms' => 0,
                'bathrooms' => 0,
                'status' => 'booked',
                'is_featured' => false,
                'label_type' => 'new',
                'custom_label_color' => '#3B82F6',
                'views_count' => 67,
                'amenities' => ['24/7 Security'],
                'owner_name' => 'Austin Development Corp',
                'owner_phone' => '+15125559876',
                'net_price' => 1900000,
                'private_notes' => 'Under contract with developer. Closing next month.',
                'watermark_enabled' => true,
                'published_at' => now()->subDays(10),
            ],
        ];

        // Insert sample properties
        foreach ($sampleProperties as $propertyData) {
            Property::create([
                ...$propertyData,
                'user_id' => $user->id,
                'created_at' => now()->subDays(rand(1, 60)),
                'updated_at' => now()->subDays(rand(0, 10)),
            ]);
        }

        $this->command->info('Sample properties created successfully!');
        $this->command->info('User: admin@example.com');
        $this->command->info('Password: password');
    }
}