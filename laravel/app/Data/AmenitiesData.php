<?php

namespace App\Data;

class AmenitiesData
{
    /**
     * Get all predefined amenities with their metadata
     */
    public static function getAll(): array
    {
        return [
            // Recreation & Leisure
            ['name' => 'Swimming Pool', 'icon' => 'pool', 'category' => 'recreation'],
            ['name' => 'Gymnasium', 'icon' => 'fitness_center', 'category' => 'fitness'],
            ['name' => 'Tennis Court', 'icon' => 'sports_tennis', 'category' => 'sports'],
            ['name' => 'Basketball Court', 'icon' => 'sports_basketball', 'category' => 'sports'],
            ['name' => 'Squash Court', 'icon' => 'sports_baseball', 'category' => 'sports'],
            ['name' => 'Jogging Track', 'icon' => 'directions_run', 'category' => 'fitness'],
            ['name' => 'Children\'s Play Area', 'icon' => 'child_friendly', 'category' => 'family'],
            ['name' => 'Barbecue Area', 'icon' => 'outdoor_grill', 'category' => 'recreation'],
            ['name' => 'Garden', 'icon' => 'yard', 'category' => 'outdoor'],
            ['name' => 'Rooftop Terrace', 'icon' => 'roofing', 'category' => 'outdoor'],

            // Security & Safety
            ['name' => '24/7 Security', 'icon' => 'security', 'category' => 'security'],
            ['name' => 'CCTV Cameras', 'icon' => 'videocam', 'category' => 'security'],
            ['name' => 'Gated Community', 'icon' => 'fence', 'category' => 'security'],
            ['name' => 'Intercom', 'icon' => 'phone', 'category' => 'security'],
            ['name' => 'Fire Alarm System', 'icon' => 'fire_extinguisher', 'category' => 'safety'],
            ['name' => 'Emergency Exits', 'icon' => 'exit_to_app', 'category' => 'safety'],

            // Parking
            ['name' => 'Covered Parking', 'icon' => 'garage', 'category' => 'parking'],
            ['name' => 'Open Parking', 'icon' => 'local_parking', 'category' => 'parking'],
            ['name' => 'Visitor Parking', 'icon' => 'directions_car', 'category' => 'parking'],
            ['name' => 'EV Charging Station', 'icon' => 'ev_station', 'category' => 'parking'],

            // Utilities & Services
            ['name' => 'Central AC', 'icon' => 'ac_unit', 'category' => 'utilities'],
            ['name' => 'Central Heating', 'icon' => 'hot_tub', 'category' => 'utilities'],
            ['name' => 'Water Heater', 'icon' => 'water_heater', 'category' => 'utilities'],
            ['name' => 'Generator Backup', 'icon' => 'power', 'category' => 'utilities'],
            ['name' => 'Elevator', 'icon' => 'elevator', 'category' => 'utilities'],
            ['name' => 'Laundry Service', 'icon' => 'local_laundry_service', 'category' => 'services'],
            ['name' => 'Housekeeping', 'icon' => 'cleaning_services', 'category' => 'services'],
            ['name' => 'Concierge Service', 'icon' => 'room_service', 'category' => 'services'],
            ['name' => 'Maintenance Staff', 'icon' => 'engineering', 'category' => 'services'],

            // Indoor Amenities
            ['name' => 'Balcony', 'icon' => 'balcony', 'category' => 'indoor'],
            ['name' => 'Terrace', 'icon' => 'deck', 'category' => 'indoor'],
            ['name' => 'Walk-in Closet', 'icon' => 'checkroom', 'category' => 'indoor'],
            ['name' => 'Built-in Wardrobes', 'icon' => 'wardrobe', 'category' => 'indoor'],
            ['name' => 'Modular Kitchen', 'icon' => 'kitchen', 'category' => 'indoor'],
            ['name' => 'Dishwasher', 'icon' => 'dishwasher', 'category' => 'indoor'],
            ['name' => 'Washing Machine', 'icon' => 'local_laundry_service', 'category' => 'indoor'],
            ['name' => 'Dryer', 'icon' => 'dry_cleaning', 'category' => 'indoor'],
            ['name' => 'Microwave', 'icon' => 'microwave', 'category' => 'indoor'],
            ['name' => 'Oven', 'icon' => 'oven', 'category' => 'indoor'],

            // Connectivity & Tech
            ['name' => 'High-Speed Internet', 'icon' => 'wifi', 'category' => 'technology'],
            ['name' => 'Cable TV', 'icon' => 'tv', 'category' => 'technology'],
            ['name' => 'Home Automation', 'icon' => 'smart_home', 'category' => 'technology'],
            ['name' => 'Sound System', 'icon' => 'speaker', 'category' => 'technology'],
            ['name' => 'Intercom System', 'icon' => 'phone_in_talk', 'category' => 'technology'],

            // Outdoor & Environment
            ['name' => 'Sea View', 'icon' => 'water', 'category' => 'views'],
            ['name' => 'City View', 'icon' => 'location_city', 'category' => 'views'],
            ['name' => 'Mountain View', 'icon' => 'landscape', 'category' => 'views'],
            ['name' => 'Garden View', 'icon' => 'park', 'category' => 'views'],
            ['name' => 'Private Garden', 'icon' => 'grass', 'category' => 'outdoor'],
            ['name' => 'Patio', 'icon' => 'outdoor_grill', 'category' => 'outdoor'],

            // Business & Work
            ['name' => 'Business Center', 'icon' => 'business_center', 'category' => 'business'],
            ['name' => 'Meeting Rooms', 'icon' => 'meeting_room', 'category' => 'business'],
            ['name' => 'Co-working Space', 'icon' => 'work', 'category' => 'business'],

            // Health & Wellness
            ['name' => 'Spa', 'icon' => 'spa', 'category' => 'wellness'],
            ['name' => 'Sauna', 'icon' => 'hot_tub', 'category' => 'wellness'],
            ['name' => 'Steam Room', 'icon' => 'shower', 'category' => 'wellness'],
            ['name' => 'Yoga Room', 'icon' => 'self_improvement', 'category' => 'wellness'],
            ['name' => 'Meditation Area', 'icon' => 'self_improvement', 'category' => 'wellness'],

            // Accessibility
            ['name' => 'Wheelchair Accessible', 'icon' => 'accessible', 'category' => 'accessibility'],
            ['name' => 'Ramp Access', 'icon' => 'ramp_left', 'category' => 'accessibility'],
            ['name' => 'Wide Doorways', 'icon' => 'door_front', 'category' => 'accessibility'],

            // Additional Amenities
            ['name' => 'Pet Friendly', 'icon' => 'pets', 'category' => 'pets'],
            ['name' => 'Storage Room', 'icon' => 'inventory', 'category' => 'storage'],
            ['name' => 'Maid Room', 'icon' => 'bedroom_parent', 'category' => 'storage'],
            ['name' => 'Study Room', 'icon' => 'school', 'category' => 'indoor'],
            ['name' => 'Library', 'icon' => 'library_books', 'category' => 'indoor'],
            ['name' => 'Cinema Room', 'icon' => 'movie', 'category' => 'entertainment'],
            ['name' => 'Game Room', 'icon' => 'games', 'category' => 'entertainment'],
            ['name' => 'Billiards Room', 'icon' => 'sports_soccer', 'category' => 'entertainment'],
        ];
    }

    /**
     * Get amenities by category
     */
    public static function getByCategory(string $category): array
    {
        return array_filter(self::getAll(), function ($amenity) use ($category) {
            return $amenity['category'] === $category;
        });
    }

    /**
     * Get all unique categories
     */
    public static function getCategories(): array
    {
        $categories = array_column(self::getAll(), 'category');
        return array_unique($categories);
    }

    /**
     * Search amenities by name
     */
    public static function search(string $query): array
    {
        if (empty($query)) {
            return self::getAll();
        }

        return array_filter(self::getAll(), function ($amenity) use ($query) {
            return stripos($amenity['name'], $query) !== false;
        });
    }
}