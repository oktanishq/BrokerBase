<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Show the property creation form
     */
    public function create()
    {
        return view('admin.properties.create');
    }

    /**
     * Store draft property data (currently just returns success)
     * This will be expanded later for actual database integration
     */
    public function storeDraft(Request $request)
    {
        // For now, just return success
        // TODO: Implement actual draft saving to database
        return response()->json([
            'success' => true,
            'message' => 'Draft saved successfully'
        ]);
    }

    /**
     * Publish a property (will be implemented later)
     */
    public function store(Request $request)
    {
        // TODO: Implement actual property creation
        // For now, just return success
        return response()->json([
            'success' => true,
            'message' => 'Property published successfully'
        ]);
    }
}