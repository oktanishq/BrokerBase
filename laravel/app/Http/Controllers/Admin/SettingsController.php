<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Services\LogoUploadService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    protected $logoUploadService;

    public function __construct(LogoUploadService $logoUploadService)
    {
        $this->logoUploadService = $logoUploadService;
    }

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
            // Validate text fields (exclude logo_url - handled separately)
            $validator = Validator::make($request->all(), [
                'agency_name' => 'required|string|max:255',
                'rera_id' => 'nullable|string|max:255',
                'w_no' => 'nullable|string|max:50',
                'office_address' => 'nullable|string',
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

            $validated = $validator->validated();

            // Handle logo upload
            if ($request->hasFile('logo')) {
                try {
                    $logoData = $this->logoUploadService->uploadLogo($request->file('logo'));
                    $validated['logo_url'] = $logoData['url'];
                } catch (\InvalidArgumentException $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Logo validation failed: ' . $e->getMessage()
                    ], 422);
                }
            }

            // If theme_color is not 'custom', remove custom_color from update (preserve existing value)
            if ($validated['theme_color'] !== 'custom' && isset($validated['custom_color'])) {
                unset($validated['custom_color']);
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
