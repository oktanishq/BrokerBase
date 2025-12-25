<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function show($id)
    {
        $properties = [
            1 => [
                'name' => 'Luxury Hillside Villa',
                'price' => 1200000,
                'location' => 'Beverly Hills, Sunset Blvd',
                'beds' => 4,
                'baths' => 5,
                'sqft' => 3500,
                'floor' => 'Ground',
                'status' => 'For Sale',
                'description' => 'Experience the pinnacle of luxury living in this exclusive villa located in the heart of Beverly Hills. Offering breathtaking city views, premium finishes throughout, and direct access to the best amenities.',
                'amenities' => ['Covered Parking', 'Swimming Pool', '24/7 Security', 'Gym & Spa', 'Concierge Service', 'Central A/C'],
                'images' => [],
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB6JGLNEeRDULjyz7WFJ4ObKCZsQi74fcb4HMbuJJ5VwEvic_qApCHMX8vJ3Knawdusc02xCJkbMMw3C-ejtmLRxazLDraIoearbjj25VNyASPqtCfC0knpA8JNNVSKy8N5tTAcfsmro8U7Rw5LHMHKXZYoI93JICpm3AQNoW2T-CsfJTCVWbaOHWDSd2KDyz10TInVv0nJFeTbQCWYKnXKBCd7GVHrXGiZeWcgHrUY88JJDScTJ-mIIz7znDdPuVAbtllQISMOXo9j',
                'map_image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCQF3U8wwqWahzqydH8tpCM9mKKqGUhz-f-IMll0FkwDcp4nlX07epV-AneVFXxuYmLtB4kPr9rgzSOnJMQ1vk6j6TvHgO7GAVG_-D29HeqQOcJhEjqlR6x2NqQqNnlbnN8BFsPN6_WgbRg9JMPth8k-xtki32fTrshtNqgmGeozCjPAWOe7jKjLW4phYIc2pAdFwlCXrxtYrH8mNTDM9ypz3GYPkTpkt6sLtDbW_VHjYDrh3FH-b2VPqHBEmI5nwPI19un-Qi8hj1n',
                'agent_name' => 'Elite Homes',
                'agent_logo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCZGvkDKAF1w7WbeFeUNmOM3NRSjHgmhSeryZM7vDVZ1m4ipcSRXPbXSEd2id5wazq_oIOrOECQqI9YWyoWlbbH2hXEX33P14Q3zghNi1ql4tBZGpuTE5NvyUY4ZTQJBmwaOlHrNFtmKJZ5hlyLxVkDdbsRnUKh523LtkEq96u8kK6SNVuz5caz2ymq71nBnay5rA4-tCzvVqaPnmBNsnRYGgYVWooVyVl0TRj85yqteKd7hSy3zjvwglp6ZBELj2yif6o7tUd4K-Hz'
            ],
            2 => [
                'name' => 'Skyline City Apartment',
                'price' => 2500,
                'location' => 'Downtown, 5th Avenue',
                'beds' => 2,
                'baths' => 2,
                'sqft' => 1100,
                'floor' => '15th',
                'status' => 'For Rent',
                'description' => 'Modern city apartment with stunning skyline views. Perfect for young professionals seeking urban living with all amenities at your doorstep.',
                'amenities' => ['Covered Parking', 'Swimming Pool', '24/7 Security', 'Gym & Spa', 'Concierge Service', 'Central A/C'],
                'images' => [],
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBT0lApEKfu4W1EpyNu2a-E_KsBSNhZJkLiCjGCYvummR4FC0a1kGMhIRQ5LRKU-4Okg66E-spvIJAXTRoGn1O--Ll6ZfgPHm5SMkYd97HoNw6rJvq09qg9Gw3_EDVQowqjfbmY4wz4d982yZUlvp9T3aSr3Zbvj4OMUC4ACM7mOtXJiaXjfPFOXvnmRg-_qDWdyzbsGcGLlxRckKV-YUWUN8-ekStNCiOYvxjBmX7EFNUFVMooeRqgUBmXYedjKo7lFLvj4oi5GxvT',
                'map_image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCQF3U8wwqWahzqydH8tpCM9mKKqGUhz-f-IMll0FkwDcp4nlX07epV-AneVFXxuYmLtB4kPr9rgzSOnJMQ1vk6j6TvHgO7GAVG_-D29HeqQOcJhEjqlR6x2NqQqNnlbnN8BFsPN6_WgbRg9JMPth8k-xtki32fTrshtNqgmGeozCjPAWOe7jKjLW4phYIc2pAdFwlCXrxtYrH8mNTDM9ypz3GYPkTpkt6sLtDbW_VHjYDrh3FH-b2VPqHBEmI5nwPI19un-Qi8hj1n',
                'agent_name' => 'Elite Homes',
                'agent_logo_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCZGvkDKAF1w7WbeFeUNmOM3NRSjHgmhSeryZM7vDVZ1m4ipcSRXPbXSEd2id5wazq_oIOrOECQqI9YWyoWlbbH2hXEX33P14Q3zghNi1ql4tBZGpuTE5NvyUY4ZTQJBmwaOlHrNFtmKJZ5hlyLxVkDdbsRnUKh523LtkEq96u8kK6SNVuz5caz2ymq71nBnay5rA4-tCzvVqaPnmBNsnRYGgYVWooVyVl0TRj85yqteKd7hSy3zjvwglp6ZBELj2yif6o7tUd4K-Hz'
            ],
            3 => [
                'name' => 'Tech Park Office Space',
                'price' => 15000,
                'location' => 'Silicon Valley, Innovation Drive',
                'beds' => null,
                'baths' => null,
                'sqft' => 2000,
                'floor' => '8th',
                'status' => 'For Rent',
                'description' => 'Premium office space in the heart of Silicon Valley. Fully equipped with modern amenities, high-speed internet, and conference facilities.',
                'amenities' => ['Covered Parking', '24/7 Security', 'Concierge Service', 'Central A/C', 'High-Speed Internet', 'Conference Rooms'],
                'images' => [],
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuC1E3fs-gJ1AVhYfRUC8Q2XGAbx_loyUQ5nQjgyZJfjbCJHzOX2-G70B8KZBIY2yoMlh4bPUpTkCEgaB-Ck7NlIXO7UB0F4C-5Od4cTJfNoadxt48BxF4gL3ilfKt_4TB1EPulqr_lxiThvhvgFG_Ymy8U5jAUKcr4xK5EDqq0vIETyRoKpjDFdwdD-6mTRTUbOp68mVQLRD6rhCFQYwQal2IOCkO2Wxx-yFMzAwpyF2WIJTBtHx1UYCwcNef2Lsjhde8z3t0QHxQnK'
            ]
        ];

        $property = $properties[$id] ?? abort(404);

        return view('property.detail', compact('property'));
    }
}