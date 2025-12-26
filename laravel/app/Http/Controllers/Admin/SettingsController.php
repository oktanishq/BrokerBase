<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Show the settings edit form.
     */
    public function edit()
    {
        $settings = Setting::getSettings();
        return view('admin.settings', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        try {
            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'agency_name' => 'required|string|max:255',
                'rera_id' => 'nullable|string|max:255',
                'w_no' => 'nullable|string|max:50',
                'office_address' => 'nullable|string',
                'logo_url' => 'nullable|url',
                'theme_color' => 'required|in:blue,emerald,red,amber,black,custom',
                'custom_color' => 'nullable|string|regex:/^[0-9A-Fa-f]{6}$/',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get the validated data
            $validated = $validator->validated();

            // If theme_color is not 'custom', set custom_color to null
            if ($validated['theme_color'] !== 'custom') {
                $validated['custom_color'] = null;
            }

            // Update or create settings
            $settings = Setting::updateSettings($validated);

            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully!',
                'settings' => $settings
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating settings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current settings (API endpoint for frontend).
     */
    public function getSettings()
    {
        try {
            $settings = Setting::getSettings();
            return response()->json([
                'success' => true,
                'data' => $settings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch settings'
            ], 500);
        }
    }
}